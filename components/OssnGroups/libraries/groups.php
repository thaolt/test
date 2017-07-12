<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    Open Social Website Core Team <info@softlab24.com>
 * @copyright 2014-2017 SOFTLAB24 LIMITED
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
 
/**
 * Get group by guid
 *
 * @param int $guid Group guid
 * @return object
 */
function ossn_get_group_by_guid($guid) {
    $group = new OssnGroup;
    return $group->getGroup($guid);
}
/**
 * Get group layout
 *
 * @param html $contents Content of page (html, php)
 * @return mixed data
 */
function ossn_group_layout($contents) {
    $content['content'] = $contents;
    return ossn_plugin_view('groups/page/group', $content);
}
/**
 * Get user groups (owned/member of)
 *
 * @param object $user User entity
 * @return object
 */
function ossn_get_user_groups($user) {
    if ($user) {
        $groups = new OssnGroup;
		//get user owned/member of groups #155
        return $groups->getMyGroups($user);
    }
}
/**
 * Group subpage set
 *
 * @param string $page Page name
 * @return void
 */
function ossn_group_subpage($page) {
    global $VIEW;
    $VIEW->pagePush[] = $page;
}
/**
 * Check if page is instace of group subpage
 *
 * @param string $page Page name
 * @return bool
 */
function ossn_is_group_subapge($page) {
    global $VIEW;
    if (in_array($page, $VIEW->pagePush)) {
        return true;
    }
    return false;
}

function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

/**
 * Get group url
 *
 * @param object $group Group entity
 * @return string
 */
function ossn_group_url($group_guid) {
    $group = ossn_get_group_by_guid($group_guid);
    $slug = ossn_slugify_str($group->title).'-'.$group_guid;

    return ossn_site_url("g/{$slug}/");
}
