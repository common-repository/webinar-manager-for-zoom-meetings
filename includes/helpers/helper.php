<?php
defined( 'ABSPATH' ) or die();

/**
 * Helper class
 */
class RZWMLiteHelperClass {
    
    public static function staff_greeting_status() {
        $current_time   = date( "H:i:s" );
        if ( $current_time < '12:00:00' ) {
            return esc_html__('Good Morning', 'staff-manger-lite' );
        }
        if ( $current_time > '12:00:00' && $current_time < '17:00:00') {
            return esc_html__('Good Afternoon ', 'staff-manger-lite' );
        }
        if ( $current_time > '17:00:00' && $current_time < '21:00:00') {
            return esc_html__('Good Evening ', 'staff-manger-lite' );
        }
        if ( $current_time > '21:00:00' && $current_time < '04:00:00') {
            return esc_html__('Good Night ', 'staff-manger-lite' );
        }
    }

    public static function get_current_user_data( $id, $value ) {
        $user          = get_userdata( $id );
        $first_name    = $user->first_name;
        $last_name     = $user->last_name;
        $user_login    = $user->user_login;
        $user_nicename = $user->user_nicename;
        $user_email    = $user->user_email;
        $display_name  = $user->display_name;

        if ( ! empty ( $value ) && $value == 'first_name' ) {
            return $user->first_name;
        } elseif( ! empty ( $value ) && $value == 'last_name' ) {
            return $user->last_name;
        } elseif( ! empty ( $value ) && $value == 'user_login' ) {
            return $user->user_login;       
        } elseif( ! empty ( $value ) && $value == 'user_nicename' ) {
            return $user->user_nicename;        
        } elseif( ! empty ( $value ) && $value == 'user_email' ) {
            return $user->user_email;           
        } elseif( ! empty ( $value ) && $value == 'display_name' ) {
            return $user->display_name;         
        } elseif( ! empty ( $value ) && $value == 'fullname' ) {
            return $user->first_name.' '.$user->last_name;
        }
    }
}