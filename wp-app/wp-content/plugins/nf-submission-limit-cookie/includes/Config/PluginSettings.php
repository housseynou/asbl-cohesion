<?php if ( ! defined( 'ABSPATH' ) ) exit;
//$api_key = Ninja_Forms()->get_setting('nf_cleverreach_api_key');
return apply_filters( 'ninja_forms_plugin_settings_submission_limit_cookie', array(
        'name'    => 'submission-limit-cookie-set',
        'type'    => 'fieldset',
        'label'   => __( 'Limit Submissions per User', 'ninja-forms-submission-limit-cookie' ),
        'width' => 'full',
        'group' => 'primary',
        'settings' => array(

            /*
             * LIMIT SUBMISSIONS
             */

            'waiting_time_between_submissions' => array(
                'name' => 'waiting_time_between_submissions',
                'type' => 'number',
                'label' => __( 'Waiting time in minutes between form submissions.', 'ninja-forms-submission-limit-cookie' ),
                'width' => 'full',
                'group' => 'primary',
                'value' => NULL,
                'help' => __('1,440 = one day<br>10,080 = one week<br>43,200 = one month<br>...','ninja-forms-submission-limit-cookie'),
            ),

            'user_sub_limit_behavior' => array(
                'name' => 'user_sub_limit_behavior',
                'type' => 'select',
                'label' => __( 'What happens when limit is reached?', 'ninja-forms-submission-limit-cookie' ),
                'options' => array(
                        array(
                            'label' => __( 'Hide form and show message', 'ninja-forms-submission-limit-cookie' ),
                            'value' => 'message-only'
                        ),
                        // array(
                        //     'label' => __( 'Hide submit button and show message', 'ninja-forms-submission-limit-cookie' ),
                        //     'value' => 'hide-submit'
                        // ),
                        array(
                            'label' => __( 'Hide form', 'ninja-forms-submission-limit-cookie' ),
                            'value' => 'hide-form'
                        ),
                    ),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'message-only',
            ),


            /*
             * LIMIT REACHED MESSAGE
             */

            'user_sub_limit_msg' => array(
                'name' => 'user_sub_limit_msg',
                'type' => 'rte',
                'label' => __( 'Limit Reached Message', 'ninja-forms' ),
                'width' => 'full',
                'group' => 'primary',
                'value' => '',
            ),
        )
));
