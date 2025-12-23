<?php

use Hostinger\Surveys\SurveyManager;
use Hostinger\WpHelper\Utils as Helper;

class Surveys {
    public const AI_SURVEY_ID       = 'ai_plugin_survey';
    public const AI_SURVEY_LOCATION = 'wordpress_ai_plugin';
    public const AI_SURVEY_PRIORITY = 70;
    public const DAY_IN_SECONDS     = 86400;
    private SurveyManager $survey_manager;

    public function __construct( SurveyManager $survey_manager ) {
        $this->survey_manager = $survey_manager;
    }

    public function init() {
        add_filter( 'hostinger_add_surveys', array( $this, 'create_surveys' ) );
    }

    public function create_surveys( $surveys ) {
        if ( $this->is_content_generation_survey_enabled() ) {
            $score_question   = esc_html__( 'How would you rate your experience using our AI Assistant plugin for content generation? (Scale 1-10)', 'hostinger-ai-assistant' );
            $comment_question = esc_html__( 'Do you have any comments/suggestions to improve our AI tools?', 'hostinger-ai-assistant' );
            $ai_survey        = SurveyManager::addSurvey( self::AI_SURVEY_ID, $score_question, $comment_question, self::AI_SURVEY_LOCATION, self::AI_SURVEY_PRIORITY );
            $surveys[]        = $ai_survey;
        }

        return $surveys;
    }

    public function is_content_generation_survey_enabled(): bool {
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return false;
        }

        $helper                  = new Helper();
        $not_completed           = $this->survey_manager->isSurveyNotCompleted( self::AI_SURVEY_ID );
        $content_published       = get_option( 'hostinger_content_published', '' );
        $is_client_eligible      = $this->survey_manager->isClientEligible();
        $is_hostinger_admin_page = $helper->isThisPage( 'hostinger-ai-assistant' );
        $is_survey_hidden        = $this->survey_manager->isSurveyHidden();

        if ( ! $is_hostinger_admin_page || $is_survey_hidden || ! $this->is_within_creation_date_limit() ) {
            return false;
        }

        return $not_completed && $content_published && $is_client_eligible;
    }

    private function is_within_creation_date_limit(): bool {
        $oldest_user = get_users(
            array(
                'number'  => 1,
                'orderby' => 'registered',
                'order'   => 'ASC',
                'fields'  => array( 'user_registered' ),
            )
        );

        $oldest_user_date = isset( $oldest_user[0]->user_registered ) ? strtotime( $oldest_user[0]->user_registered ) : false;

        return $oldest_user_date && ( time() - $oldest_user_date ) <= ( 7 * self::DAY_IN_SECONDS );
    }
}
