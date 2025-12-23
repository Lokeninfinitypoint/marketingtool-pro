<?php
if ( ! class_exists( 'Iteck_User_Social_Profiles' ) ) {

    class Iteck_User_Social_Profiles {
        
        //Additional contact methods
        function new_contactmethods( $user_contact ) {
            //Twitter
            $user_contact['twitter'] = __( 'Twitter', 'user-social-profiles');
            //Facebook
            $user_contact['facebook'] = __( 'Facebook' , 'user-social-profiles');
            //Instagram
            $user_contact['instagram'] = __( 'Instagram' , 'user-social-profiles');
            //Pinterest
            $user_contact['pinterest'] = __( 'Pinterest', 'user-social-profiles');

            return $user_contact;
        }

        function __construct() {
            add_filter('user_contactmethods', array( $this, 'new_contactmethods' ) ,10,1);
        }
        
    }

    new Iteck_User_Social_Profiles;

}