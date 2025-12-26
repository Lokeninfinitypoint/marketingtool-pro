/**
 * Appwrite Configuration & Client Setup
 * Backend Foundation: Authentication, Database, Storage, Permissions
 */

import { Client, Account, Databases, Storage, ID, Query } from 'appwrite';

// Appwrite Configuration
const APPWRITE_ENDPOINT = process.env.NEXT_PUBLIC_APPWRITE_ENDPOINT || 'https://cloud.appwrite.io/v1';
const APPWRITE_PROJECT_ID = process.env.NEXT_PUBLIC_APPWRITE_PROJECT_ID || '';
const APPWRITE_DATABASE_ID = process.env.NEXT_PUBLIC_APPWRITE_DATABASE_ID || 'main';
const APPWRITE_STORAGE_ID = process.env.NEXT_PUBLIC_APPWRITE_STORAGE_ID || 'files';

// Collections
export const COLLECTIONS = {
  USERS: 'users',
  PLANS: 'plans',
  CREDITS: 'credits',
  TOOL_CONFIGS: 'tool_configs',
  SETTINGS: 'settings',
} as const;

// Buckets
export const BUCKETS = {
  ASSETS: 'assets',
  REPORTS: 'reports',
  EXPORTS: 'exports',
} as const;

// Initialize Appwrite Client
export const appwriteClient = new Client()
  .setEndpoint(APPWRITE_ENDPOINT)
  .setProject(APPWRITE_PROJECT_ID);

// Services
export const account = new Account(appwriteClient);
export const databases = new Databases(appwriteClient);
export const storage = new Storage(appwriteClient);

// Helper: Get current user
export async function getCurrentUser() {
  try {
    return await account.get();
  } catch (error) {
    return null;
  }
}

// Helper: Check if user is authenticated
export async function isAuthenticated(): Promise<boolean> {
  try {
    await account.get();
    return true;
  } catch {
    return false;
  }
}

export { ID, Query };
