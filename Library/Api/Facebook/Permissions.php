<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Api\Facebook;


class Permissions
{
    public static function get()
    {
        $permissions = []; // Optional permissions
        $permissions[] = 'public_profile';
        $permissions[] = 'user_friends';
        $permissions[] = 'email';
        $permissions[] = 'user_about_me';
        $permissions[] = 'user_actions.books';
        $permissions[] = 'user_actions.fitness';
        $permissions[] = 'user_actions.music';
        $permissions[] = 'user_actions.news';
        $permissions[] = 'user_actions.video';
        $permissions[] = 'user_birthday';
        $permissions[] = 'user_education_history';
        $permissions[] = 'user_events';
        $permissions[] = 'user_games_activity';
        $permissions[] = 'user_hometown';
        $permissions[] = 'user_likes';
        $permissions[] = 'user_location';
        $permissions[] = 'user_managed_groups';
        $permissions[] = 'user_photos';
        $permissions[] = 'user_posts';
        $permissions[] = 'user_relationships';
        $permissions[] = 'user_relationship_details';
        $permissions[] = 'user_religion_politics';
        $permissions[] = 'user_tagged_places';
        $permissions[] = 'user_videos';
        $permissions[] = 'user_website';
        $permissions[] = 'user_work_history';
        $permissions[] = 'read_custom_friendlists';
        $permissions[] = 'read_insights';
        $permissions[] = 'read_audience_network_insights';
        $permissions[] = 'read_page_mailboxes';
        $permissions[] = 'manage_pages';
        $permissions[] = 'publish_pages';
        $permissions[] = 'publish_actions';
        $permissions[] = 'rsvp_event';
        $permissions[] = 'pages_show_list';
        $permissions[] = 'pages_manage_cta';
        $permissions[] = 'pages_manage_instant_articles';
        $permissions[] = 'ads_read';
        $permissions[] = 'ads_management';
        return $permissions;
    }


}