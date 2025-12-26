/**
 * Windmill Automation Service
 * Handles: Ads automation, Scheduled jobs, Campaign optimization, Meta API syncing
 */

import { windmillClient, WorkflowRun, ScheduledJob } from './windmill';

export interface WorkflowInput {
  [key: string]: any;
}

export interface AutomationConfig {
  workflowPath: string;
  inputs: WorkflowInput;
  timeout?: number;
}

/**
 * Windmill Automation Service
 */
export class WindmillService {
  /**
   * Run workflow synchronously
   */
  static async runWorkflow(config: AutomationConfig): Promise<WorkflowRun> {
    try {
      const response = await windmillClient.post('/w/run', {
        path: config.workflowPath,
        input: config.inputs,
        timeout: config.timeout || 300,
      });
      return response.data;
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Workflow execution failed');
    }
  }

  /**
   * Run workflow asynchronously
   */
  static async runWorkflowAsync(config: AutomationConfig): Promise<string> {
    try {
      const response = await windmillClient.post('/w/run_async', {
        path: config.workflowPath,
        input: config.inputs,
      });
      return response.data.job_id;
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Workflow execution failed');
    }
  }

  /**
   * Get workflow run status
   */
  static async getWorkflowRun(jobId: string): Promise<WorkflowRun> {
    try {
      const response = await windmillClient.get(`/jobs/get_result/${jobId}`);
      return response.data;
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to get workflow status');
    }
  }

  /**
   * Cancel workflow run
   */
  static async cancelWorkflowRun(jobId: string): Promise<void> {
    try {
      await windmillClient.post(`/jobs/cancel/${jobId}`);
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to cancel workflow');
    }
  }

  /**
   * Ads Automation Workflows
   */
  
  /**
   * Optimize campaign
   */
  static async optimizeCampaign(campaignId: string, config: Record<string, any> = {}): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/ads/optimize_campaign',
      inputs: {
        campaign_id: campaignId,
        ...config,
      },
    });
  }

  /**
   * Sync Meta API data
   */
  static async syncMetaAPI(accountId: string, syncType: 'campaigns' | 'ads' | 'all' = 'all'): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/ads/sync_meta_api',
      inputs: {
        account_id: accountId,
        sync_type: syncType,
      },
    });
  }

  /**
   * Auto-responder for comments
   */
  static async autoRespondComments(postId: string, responseTemplate: string): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/ads/auto_respond_comments',
      inputs: {
        post_id: postId,
        response_template: responseTemplate,
      },
    });
  }

  /**
   * Rule-based campaign trigger
   */
  static async triggerRuleBasedAction(ruleId: string, triggerData: Record<string, any>): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/ads/rule_based_trigger',
      inputs: {
        rule_id: ruleId,
        trigger_data: triggerData,
      },
    });
  }

  /**
   * Scheduled Jobs
   */

  /**
   * Create scheduled job
   */
  static async createScheduledJob(
    workflowPath: string,
    schedule: string, // Cron expression
    inputs: WorkflowInput = {},
    enabled: boolean = true
  ): Promise<ScheduledJob> {
    try {
      const response = await windmillClient.post('/schedules/create', {
        path: workflowPath,
        schedule,
        input: inputs,
        enabled,
      });
      return response.data;
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to create scheduled job');
    }
  }

  /**
   * Get scheduled jobs
   */
  static async getScheduledJobs(): Promise<ScheduledJob[]> {
    try {
      const response = await windmillClient.get('/schedules/list');
      return response.data.schedules || [];
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to fetch scheduled jobs');
    }
  }

  /**
   * Update scheduled job
   */
  static async updateScheduledJob(jobId: string, updates: Partial<ScheduledJob>): Promise<ScheduledJob> {
    try {
      const response = await windmillClient.post(`/schedules/update/${jobId}`, updates);
      return response.data;
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to update scheduled job');
    }
  }

  /**
   * Delete scheduled job
   */
  static async deleteScheduledJob(jobId: string): Promise<void> {
    try {
      await windmillClient.delete(`/schedules/delete/${jobId}`);
    } catch (error: any) {
      throw new Error(error.response?.data?.error || error.message || 'Failed to delete scheduled job');
    }
  }

  /**
   * Daily/Weekly Reporting
   */
  static async scheduleDailyReport(userId: string, reportConfig: Record<string, any>): Promise<ScheduledJob> {
    return this.createScheduledJob(
      'f/reports/daily_report',
      '0 9 * * *', // Daily at 9 AM
      {
        user_id: userId,
        ...reportConfig,
      }
    );
  }

  static async scheduleWeeklyReport(userId: string, reportConfig: Record<string, any>): Promise<ScheduledJob> {
    return this.createScheduledJob(
      'f/reports/weekly_report',
      '0 9 * * 1', // Weekly on Monday at 9 AM
      {
        user_id: userId,
        ...reportConfig,
      }
    );
  }

  /**
   * ETL & Data Processing
   */
  static async runETLJob(source: string, destination: string, transformConfig: Record<string, any>): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/etl/process_data',
      inputs: {
        source,
        destination,
        transform: transformConfig,
      },
    });
  }

  /**
   * AI Agent Orchestration
   */
  static async runAIAgent(agentId: string, task: string, context: Record<string, any> = {}): Promise<WorkflowRun> {
    return this.runWorkflow({
      workflowPath: 'f/ai/run_agent',
      inputs: {
        agent_id: agentId,
        task,
        context,
      },
    });
  }
}
