<?php

function is_available($coupon): bool
{
    $meta = get_post_meta($coupon->ID);
    $usage_limit = $meta['usage_limit'][0];
    $usage_count = $meta['usage_count'][0];
    $usage_limit_per_user = $meta['usage_limit_per_user'][0];
    $used_by = $meta['_used_by'];
    $expires_date = $meta['date_expires'][0];


    $user = wp_get_current_user();
    $email = $user->data->user_email;

    if (isset($meta['_wt_sc_user_roles']) && $meta['_wt_sc_user_roles'][0] != "") {
        $flag = false;
        foreach (array_values($user->roles) as $role) {
            if (in_array($role, $meta['_wt_sc_user_roles'])) {
                $flag = true;
            }
        }

        if (!$flag) {
            return false;
        }
    }

        if ($expires_date) {
            if (time() > $expires_date)
                return false;
        }

           if (isset($meta['customer_email'])) {
               if (!strpos($meta['customer_email'][0], $email)) {
                   return false;
               }
           }

              if ($usage_limit != 0 && $usage_limit <= $usage_count) {
                  return false;
              }

                  if ($usage_limit_per_user != 0 && $meta['_used_by']) {

                      $counter = 0;
                      foreach ($used_by as $used_id) {
                          $counter++;
                      }

                      if ($counter >= $usage_limit_per_user) {
                          return false;
                      }
                  }


    return true;
}