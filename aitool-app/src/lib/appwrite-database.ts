/**
 * Appwrite Database Service
 * Handles: User settings, Plans, Credits, Tool configs
 */

import { databases, ID, Query, COLLECTIONS } from './appwrite';
import type { Models } from 'appwrite';

export interface UserSettings {
  theme?: 'light' | 'dark';
  notifications?: boolean;
  language?: string;
  timezone?: string;
  [key: string]: any;
}

export interface Plan {
  id: string;
  name: string;
  price: number;
  credits: number;
  features: string[];
  active: boolean;
}

export interface Credit {
  id: string;
  userId: string;
  amount: number;
  source: 'purchase' | 'bonus' | 'refund';
  createdAt: string;
}

export interface ToolConfig {
  id: string;
  userId: string;
  toolId: string;
  config: Record<string, any>;
  enabled: boolean;
}

/**
 * Database Service
 */
export class DatabaseService {
  private static databaseId = process.env.NEXT_PUBLIC_APPWRITE_DATABASE_ID || 'main';

  /**
   * User Settings
   */
  static async getUserSettings(userId: string): Promise<UserSettings | null> {
    try {
      const settings = await databases.getDocument(
        this.databaseId,
        COLLECTIONS.SETTINGS,
        userId
      );
      return settings as unknown as UserSettings;
    } catch {
      return null;
    }
  }

  static async updateUserSettings(userId: string, settings: Partial<UserSettings>): Promise<UserSettings> {
    try {
      const updated = await databases.updateDocument(
        this.databaseId,
        COLLECTIONS.SETTINGS,
        userId,
        settings
      );
      return updated as unknown as UserSettings;
    } catch (error: any) {
      // Create if doesn't exist
      if (error.code === 404) {
        const created = await databases.createDocument(
          this.databaseId,
          COLLECTIONS.SETTINGS,
          userId,
          { userId, ...settings }
        );
        return created as unknown as UserSettings;
      }
      throw new Error(error.message || 'Failed to update settings');
    }
  }

  /**
   * Plans
   */
  static async getPlans(): Promise<Plan[]> {
    try {
      const response = await databases.listDocuments(
        this.databaseId,
        COLLECTIONS.PLANS,
        [Query.equal('active', true)]
      );
      return response.documents as unknown as Plan[];
    } catch (error: any) {
      throw new Error(error.message || 'Failed to fetch plans');
    }
  }

  static async getPlan(planId: string): Promise<Plan> {
    try {
      const plan = await databases.getDocument(
        this.databaseId,
        COLLECTIONS.PLANS,
        planId
      );
      return plan as unknown as Plan;
    } catch (error: any) {
      throw new Error(error.message || 'Failed to fetch plan');
    }
  }

  /**
   * Credits
   */
  static async getUserCredits(userId: string): Promise<number> {
    try {
      const credits = await databases.listDocuments(
        this.databaseId,
        COLLECTIONS.CREDITS,
        [
          Query.equal('userId', userId),
          Query.orderDesc('createdAt'),
        ]
      );
      
      // Sum all credits
      return credits.documents.reduce((sum: number, credit: any) => sum + (credit.amount || 0), 0);
    } catch {
      return 0;
    }
  }

  static async addCredits(userId: string, amount: number, source: 'purchase' | 'bonus' | 'refund' = 'purchase'): Promise<Credit> {
    try {
      const credit = await databases.createDocument(
        this.databaseId,
        COLLECTIONS.CREDITS,
        ID.unique(),
        {
          userId,
          amount,
          source,
        }
      );
      return credit as unknown as Credit;
    } catch (error: any) {
      throw new Error(error.message || 'Failed to add credits');
    }
  }

  static async getCreditHistory(userId: string, limit: number = 50): Promise<Credit[]> {
    try {
      const response = await databases.listDocuments(
        this.databaseId,
        COLLECTIONS.CREDITS,
        [
          Query.equal('userId', userId),
          Query.orderDesc('createdAt'),
          Query.limit(limit),
        ]
      );
      return response.documents as unknown as Credit[];
    } catch (error: any) {
      throw new Error(error.message || 'Failed to fetch credit history');
    }
  }

  /**
   * Tool Configs
   */
  static async getToolConfigs(userId: string): Promise<ToolConfig[]> {
    try {
      const response = await databases.listDocuments(
        this.databaseId,
        COLLECTIONS.TOOL_CONFIGS,
        [Query.equal('userId', userId)]
      );
      return response.documents as unknown as ToolConfig[];
    } catch (error: any) {
      throw new Error(error.message || 'Failed to fetch tool configs');
    }
  }

  static async getToolConfig(userId: string, toolId: string): Promise<ToolConfig | null> {
    try {
      const response = await databases.listDocuments(
        this.databaseId,
        COLLECTIONS.TOOL_CONFIGS,
        [
          Query.equal('userId', userId),
          Query.equal('toolId', toolId),
        ]
      );
      return response.documents[0] as unknown as ToolConfig || null;
    } catch {
      return null;
    }
  }

  static async saveToolConfig(userId: string, toolId: string, config: Record<string, any>, enabled: boolean = true): Promise<ToolConfig> {
    try {
      // Check if exists
      const existing = await this.getToolConfig(userId, toolId);
      
      if (existing) {
        const updated = await databases.updateDocument(
          this.databaseId,
          COLLECTIONS.TOOL_CONFIGS,
          existing.id,
          { config, enabled }
        );
        return updated as unknown as ToolConfig;
      } else {
        const created = await databases.createDocument(
          this.databaseId,
          COLLECTIONS.TOOL_CONFIGS,
          ID.unique(),
          { userId, toolId, config, enabled }
        );
        return created as unknown as ToolConfig;
      }
    } catch (error: any) {
      throw new Error(error.message || 'Failed to save tool config');
    }
  }
}
