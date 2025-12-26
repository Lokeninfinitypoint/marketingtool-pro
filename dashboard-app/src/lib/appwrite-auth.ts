/**
 * Appwrite Authentication Service
 * Handles: Signup, Login, Sessions, OAuth, User Management
 */

import { account, ID } from './appwrite';
import type { Models } from 'appwrite';

export interface SignupData {
  email: string;
  password: string;
  name?: string;
}

export interface LoginData {
  email: string;
  password: string;
}

export interface User extends Models.User<Models.Preferences> {
  name?: string;
  plan?: string;
  credits?: number;
}

/**
 * Authentication Service
 */
export class AuthService {
  /**
   * Sign up new user
   */
  static async signup(data: SignupData): Promise<User> {
    try {
      const user = await account.create(ID.unique(), data.email, data.password, data.name);
      return user as User;
    } catch (error: any) {
      throw new Error(error.message || 'Signup failed');
    }
  }

  /**
   * Login user
   */
  static async login(data: LoginData): Promise<Models.Session> {
    try {
      const session = await account.createEmailPasswordSession(data.email, data.password);
      return session;
    } catch (error: any) {
      throw new Error(error.message || 'Login failed');
    }
  }

  /**
   * Logout user
   */
  static async logout(): Promise<void> {
    try {
      await account.deleteSession('current');
    } catch (error: any) {
      throw new Error(error.message || 'Logout failed');
    }
  }

  /**
   * Get current user
   */
  static async getCurrentUser(): Promise<User | null> {
    try {
      const user = await account.get();
      return user as User;
    } catch {
      return null;
    }
  }

  /**
   * Get current session
   */
  static async getCurrentSession(): Promise<Models.Session | null> {
    try {
      const sessions = await account.listSessions();
      return sessions.sessions[0] || null;
    } catch {
      return null;
    }
  }

  /**
   * OAuth Login (Google, GitHub, etc.)
   */
  static async oauthLogin(provider: 'google' | 'github' | 'facebook', redirectUrl?: string): Promise<void> {
    try {
      const redirect = redirectUrl || window.location.origin + '/auth/callback';
      await account.createOAuth2Session(provider, redirect, redirect);
    } catch (error: any) {
      throw new Error(error.message || 'OAuth login failed');
    }
  }

  /**
   * Send password reset email
   */
  static async sendPasswordReset(email: string, redirectUrl?: string): Promise<void> {
    try {
      const redirect = redirectUrl || window.location.origin + '/auth/reset-password';
      await account.createRecovery(email, redirect);
    } catch (error: any) {
      throw new Error(error.message || 'Password reset failed');
    }
  }

  /**
   * Update password
   */
  static async updatePassword(password: string, oldPassword?: string): Promise<void> {
    try {
      if (oldPassword) {
        await account.updatePassword(password, oldPassword);
      } else {
        await account.updatePassword(password);
      }
    } catch (error: any) {
      throw new Error(error.message || 'Password update failed');
    }
  }

  /**
   * Update user name
   */
  static async updateName(name: string): Promise<User> {
    try {
      const user = await account.updateName(name);
      return user as User;
    } catch (error: any) {
      throw new Error(error.message || 'Name update failed');
    }
  }

  /**
   * Update user email
   */
  static async updateEmail(email: string, password: string): Promise<User> {
    try {
      const user = await account.updateEmail(email, password);
      return user as User;
    } catch (error: any) {
      throw new Error(error.message || 'Email update failed');
    }
  }
}
