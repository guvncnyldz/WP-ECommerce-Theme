<?php

function my_register($data,$user_login = false,$social_login = false)
{
    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $password = $data['password'];

    if(!$user_login)
    {
        $user_login = $data['email'];
    }

    $error = false;
    if (!$error && empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = true;
        alert("Lütfen tüm alanları doldurunuz");
    }

    if (!$error && !is_email($email)) {
        $error = true;
        alert("Hatalı e-posta adresi");
    }

    if (!$error && email_exists($email)) {
        $error = true;
        alert("E-posta adresi kullanılmakta");
    }

    if (!$error) {
        // sanitize user form input
        $email = sanitize_email($email);

        $userdata = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
        );
        $user = wc_create_new_customer($email,$user_login, $password, $userdata);

        if (isset($_POST['newsletter'])) {
            update_user_meta($user, "newsletter", 'true');
        } else {
            update_user_meta($user, "newsletter", 'false');
        }

        update_user_meta($user, "billing_country", "TR");
        update_user_meta($user, "shipping_country", "TR");

        if($social_login)
        {
            add_user_meta($user, "social-login", "1");
        }
    }

    return !$error;
}

function my_login($data)
{
    $email = $data['email'];
    $password = $data['password'];

    $error = false;
    if (!$error && empty($email) || empty($password)) {
        $error = true;
        alert("Lütfen gerekli alanları doldurunuz");
    }

    if (!$error) {
        $user = get_user_by('email', $email);
        if (!$user) {
            $error = true;
            alert("Hatalı şifre ya da e-posta girdiniz");
        } else { //check password
            if (!wp_check_password($password, $user->user_pass, $user->ID)) { //bad password
                $error = true;
                alert("Hatalı şifre ya da e-posta girdiniz");
            } else {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
            }
        }
    }

    return !$error;
}