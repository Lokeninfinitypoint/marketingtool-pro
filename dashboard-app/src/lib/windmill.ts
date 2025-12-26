/**
 * Windmill Configuration & Client Setup
 * Automation & Job Engine: Workflows, Scheduled Jobs, AI Agents
 */

import axios, { AxiosInstance } from 'axios';

// Windmill Configuration
const WINDMILL_ENDPOINT = process.env.NEXT_PUBLIC_WINDMILL_ENDPOINT || 'https://app.windmill.dev';
const WINDMILL_TOKEN = process.env.NEXT_PUBLIC_WINDMILL_TOKEN || '';

// Initialize Windmill Client
export const windmillClient: AxiosInstance = axios.create({
  baseURL: `${WINDMILL_ENDPOINT}/api/v1`,
  headers: {
    'Authorization': `Bearer ${WINDMILL_TOKEN}`,
    'Content-Type': 'application/json',
  },
});

export interface WorkflowRun {
  id: string;
  status: 'running' | 'success' | 'failed' | 'cancelled';
  result?: any;
  error?: string;
}

export interface ScheduledJob {
  id: string;
  path: string;
  schedule: string;
  enabled: boolean;
}
