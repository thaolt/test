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
$cover = $params['group']->haveCover();
$cover_left = '';
$iscover = '';
$groupUrl = '';
$avatarUrl = $params['group']->avatarURL("larger");

if ($cover) {

    $groupUrl = $params['group']->coverURL();
    $iscover = 'ossn-group-cover-header';
    $coverp = $params['group']->coverParameters($params['group']->guid);
    if(!empty($coverp[0])){
		$cover_top = "top:{$coverp[0]}px;";
	}
	if(!empty($coverp[1])){
	    $cover_left = "left:{$coverp[1]}px;";
	}
} else {
    $groupUrl = ossn_site_url("groups/cover/".md5($params['group']->title).'.jpg');
}


//group members total count becomes 0 when group cover is set #156 $dev.githubertus
$members = $params['group']->getMembers();
?>
<div class="ossn-group-profile">
	<div class="ossn-group-top-row">
    <div class="col-md-11">
    	<div class="profile-header <?php echo $iscover; ?>">
        <?php if ($params['group']->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin()) { ?>

            <form id="group-upload-cover" style="display:none;" method="post" enctype="multipart/form-data">
                <input type="file" name="coverphoto" class="coverfile"
                       onchange="Ossn.Clk('#group-upload-cover .upload');"/>
                <input type="hidden" value="<?php echo $params['group']->guid; ?>" name="group"/>
                <input type="submit" class="upload"/>
            </form>

        <?php
        }

        ?>

        <div class="ossn-group-cover" id="container">
           <?php if ($params['group']->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin()) { ?>
                <div class="ossn-group-cover-button ">
                    <a href="javascript:void(0);" id="reposition-cover"
                       class='button-grey'><?php echo ossn_print('reposition:cover'); ?></a>
                    <a href="javascript:void(0);" id="add-cover-group"
                       class='button-grey'><?php echo ossn_print('change:cover'); ?></a>
                </div>
            <?php } ?>
            <img id="draggable" src="<?php echo $groupUrl; ?>"
                 style='<?php echo $cover_top; ?><?php echo $cover_left; ?>'/>
        </div>
        <div class="group-groupname"><?php echo $params['group']->title; ?></div>

        <div class="profile-photo groups-photo">
            <?php if ($params['group']->owner_guid == ossn_loggedin_user()->guid) { ?>
                <div class="upload-photo" style="display:none;cursor:pointer;">
                    <span onclick="Ossn.Clk('.pfile');"><?php echo ossn_print('change:photo'); ?></span>
                    <form id="groups-upload-photo" style="display:none;" method="post" enctype="multipart/form-data">
                        <input type="file" name="userphoto" class="pfile" onchange="Ossn.Clk('#groups-upload-photo .upload');" />
                        <input type="hidden" value="<?php echo $params['group']->guid; ?>" name="group"/>
                        <input type="submit" class="upload" />
                    </form>
                </div>
            <?php } ?>
            <img src="<?php echo $avatarUrl ?>" height="170" width="170"/>
        </div>
        <div class="header-bottom">
            <div class="group-name">
                <a href="<?php echo ossn_group_url($params['group']->guid); ?>"><?php echo ossn_print('news:feed'); ?></a>
            </div>
            <div id="group-header-menu" class="group-header-menu">
                <ul>
                    <?php echo ossn_view_menu('groupheader'); ?>
                </ul>
            </div>
            <div class="groups-buttons">
            <?php
            if ($params['group']->owner_guid == ossn_loggedin_user()->guid)
               $isInvite = true;
            else if ($params['group']->membInvite ==  1) {
                if($params['group']->isMember(NULL, ossn_loggedin_user()->guid))
                    $isInvite = true;
            } else
                $isInvite = false;

            if ($isInvite) { ?>

                <a href="javascript:void(0);" id="group-member-invite" data-guid="<?php echo $params['group']->guid; ?>"
                   class='btn btn-success'><?php echo ossn_print('group:invite'); ?></a>
            <?php } ?>

            <?php if (ossn_isLoggedin() && $params['group']->owner_guid !== ossn_loggedin_user()->guid) {

                    if ($params['group']->isMember(NULL, ossn_loggedin_user()->guid)) {
                        $ismember = 1;
                        ?>
                        <a href="<?php echo ossn_site_url("action/group/member/cancel?group={$params['group']->guid}", true); ?>"
                           class='btn btn-default'> <?php echo ossn_print('leave:group'); ?></a>
                    <?php
                    } else if ((!$params['group']->requestExists(ossn_loggedin_user()->guid, false)) &&
                            ($params['group']->groupMembership != MEMBERSHIP_INVITE_ONLY ) &&
                            (!$params['group']->inviteExists(ossn_loggedin_user()->guid,false))) {
                        ?>
                        <a href="<?php echo ossn_site_url("action/group/join?group={$params['group']->guid}", true); ?>"
                           class='btn btn-default'> <?php echo ossn_print('join:group'); ?></a>
                    <?php
                    }

                    if (!$ismember && $params['group']->requestExists(ossn_loggedin_user()->guid, false)) {
                        ?>
                        <a href="<?php echo ossn_site_url("action/group/member/cancel?group={$params['group']->guid}", true); ?>"
                           class='btn btn-default'> <?php echo ossn_print('cancel:membership'); ?></a>
                    <?php }

                    // check group invite exits and not a member
                    if (!$ismember && $params['group']->inviteExists(ossn_loggedin_user()->guid,false)) {

                        $current_user_id = ossn_loggedin_user()->guid;
                        $current_group_id = $params['group']->guid;
                    ?>
                        <a href="<?php echo ossn_site_url("action/group/member/accept?group={$current_group_id}&user={$current_user_id}", true); ?>"
                           class='btn btn-success'> <?php echo ossn_print('group:invite:accept'); ?></a>

                        <a href="<?php echo ossn_site_url("action/group/member/reject?group={$current_group_id}&user={$current_user_id}", true); ?>"
                           class='btn btn-danger'> <?php echo ossn_print('group:invite:reject'); ?></a>
                    <?php } ?>
            <?php } ?>

            <?php  if ($params['group']->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin()) {
                $ismember = 1;
                ?>
                <a href="<?php echo ossn_group_url($params['group']->guid); ?>edit"
                   class='btn btn-default'><?php echo ossn_print('settings'); ?></a>
                <a href="javascript:void(0);" onclick="Ossn.repositionGroupCOVER(<?php echo $params['group']->guid; ?>);"
                   class='btn btn-default group-c-position'><?php echo ossn_print('save:position'); ?></a>
            <?php } ?>
           	</div>

       	</div>
    </div>
    </div>
    </div> <!-- ./row -->
    <div class="ossn-group-bottom-row">
    	<?php
		if (isset($params['subpage']) && !empty($params['subpage']) && ossn_is_group_subapge($params['subpage'])) {
            if (ossn_is_hook('group', 'subpage')) {
                echo ossn_call_hook('group', 'subpage', $params);
            }
        }  else {
		?>
        <div class="col-md-7 margin-top-10">
        	<div class="group-wall">
                <?php
			//#113 make contents of public groups visible.
			//send ismember, and member ship param to group wall
                	echo ossn_plugin_view('wall/group', array(
									'group' => $params,
									'ismember' => $ismember,
									'membership' => $params['group']->membership
									));
                if ($params['group']->membership == OSSN_PRIVATE && $ismember !== 1) {
					$close_group_text = "<p>".ossn_print('close:group:notice')."</p>";
                    ?>
                    <div class="group-closed-container">
						<?php
                        echo ossn_view_widget(array(
											'title' => ossn_print('closed:group'),
											'contents' => $close_group_text
						));
						?>
                        <div class="group-members-small">
                            <?php
                             $group_admin = ossn_user_by_guid($params['group']->owner_guid);
							 $admin_img =  '<img src="'.$group_admin->iconURL()->small.'" title="'.$group_admin->fullname.'"/>';
							 $admin_profile_url = ossn_plugin_view('output/url', array(
										'text' => $admin_img,
										'href' => $group_admin->profileURL()
							 ));
							echo ossn_view_widget(array(
											'title' => ossn_print('group:admin'),
											'contents' => $admin_profile_url
							));
							?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="col-md-4 margin-top-10">
        	<div class="page-sidebar">
        	<?php
                $groupAbout = ossn_plugin_view('groups/widget/about', array(
                                'group' => $params['group'],
                                ));

    			echo ossn_view_widget(array(
    								'title' => ossn_print('about:group'),
    								'contents' => $groupAbout,
    								'class' => 'widget-description',
    			));
			if ($params['group']->owner_guid == ossn_loggedin_user()->guid || ossn_isAdminLoggedin()) {
				$member_requests = ossn_plugin_view('output/url', array(
										'text' => ossn_print('view:all'),
										'href' => ossn_group_url($params['group']->guid).'requests'
							 ));
				$requests = $params['group']->countRequests();
				if($requests === false){
					$requests = 0;
				}
				echo ossn_view_widget(array(
								'title' => ossn_print('member:requests', array($requests)),
								'contents' => $member_requests,
								'class' => 'group-requests-widget',
				));
			}
			$members = $params['group']->getMembers();
            $limit = 1;
            if($members) {
                    foreach ($members as $member) {
                        if ($limit <= 10) {
							 $img =  '<img src="'.$member->iconURL()->small.'" title="'.$member->fullname.'"/>';
							 $profile_url = ossn_plugin_view('output/url', array(
												'text' => $img,
												'href' => $member->profileURL()
											 ));
                             $members_html[] = $profile_url;
                             $limit++;
                        }
					}
						echo '<div class="group-widget-members">';
						echo ossn_view_widget(array(
													'title' => ossn_print('group:members', array(count($members))),
													'contents' => implode('', $members_html)
													));
						echo '</div>';
            }
			if (ossn_is_hook('group', 'widgets')){
								$params['group'] = $params['group'];
								$modules = ossn_call_hook('group', 'widgets', $params);
								echo implode( '', $modules);
			}
			 if (com_is_active('OssnAds')) {
                    echo ossn_plugin_view('ads/page/view');
                }
             ?>
             </div>
        </div>
          <?php
                }
                ?>
    </div>
</div>
