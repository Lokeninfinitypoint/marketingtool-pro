/**
 * Appwrite Storage Service
 * Handles: Assets, Reports, Exports file storage
 */

import { storage, ID, BUCKETS } from './appwrite';

/**
 * Storage Service
 */
export class StorageService {
  /**
   * Upload file to storage
   */
  static async uploadFile(
    bucketId: string,
    file: File,
    fileId?: string
  ): Promise<string> {
    try {
      const id = fileId || ID.unique();
      await storage.createFile(bucketId, id, file);
      return id;
    } catch (error: any) {
      throw new Error(error.message || 'File upload failed');
    }
  }

  /**
   * Get file URL
   */
  static getFileUrl(bucketId: string, fileId: string): string {
    return storage.getFileView(bucketId, fileId).toString();
  }

  /**
   * Get file download URL
   */
  static getFileDownloadUrl(bucketId: string, fileId: string): string {
    return storage.getFileDownload(bucketId, fileId).toString();
  }

  /**
   * Delete file
   */
  static async deleteFile(bucketId: string, fileId: string): Promise<void> {
    try {
      await storage.deleteFile(bucketId, fileId);
    } catch (error: any) {
      throw new Error(error.message || 'File deletion failed');
    }
  }

  /**
   * List files in bucket
   */
  static async listFiles(bucketId: string, queries?: string[]): Promise<any[]> {
    try {
      const response = await storage.listFiles(bucketId, queries);
      return response.files;
    } catch (error: any) {
      throw new Error(error.message || 'Failed to list files');
    }
  }

  /**
   * Upload asset (image, document, etc.)
   */
  static async uploadAsset(file: File, fileId?: string): Promise<string> {
    return this.uploadFile(BUCKETS.ASSETS, file, fileId);
  }

  /**
   * Upload report
   */
  static async uploadReport(file: File, fileId?: string): Promise<string> {
    return this.uploadFile(BUCKETS.REPORTS, file, fileId);
  }

  /**
   * Upload export
   */
  static async uploadExport(file: File, fileId?: string): Promise<string> {
    return this.uploadFile(BUCKETS.EXPORTS, file, fileId);
  }

  /**
   * Get asset URL
   */
  static getAssetUrl(fileId: string): string {
    return this.getFileUrl(BUCKETS.ASSETS, fileId);
  }

  /**
   * Get report URL
   */
  static getReportUrl(fileId: string): string {
    return this.getFileUrl(BUCKETS.REPORTS, fileId);
  }

  /**
   * Get export URL
   */
  static getExportUrl(fileId: string): string {
    return this.getFileUrl(BUCKETS.EXPORTS, fileId);
  }
}
