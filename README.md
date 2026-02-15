<?php
/*
Plugin Name: MI - Millores Portafoli
Description: Afegeix contingut automàtic al final de les entrades, modifica títols de pàgina i executa una acció en crear entrades.
Version: 1.0
Author: Miguel Ibañez
*/

if (!defined('ABSPATH')) exit;

/**
 * (1) Afegeix contingut automàtic al final de les entrades (FILTER the_content)
 * Mostra 3 entrades recents del mateix WordPress.
 */
add_filter('the_content', 'pm_afegir_extra_al_final');

function pm_afegir_extra_al_final($content) {
    // Només en entrades individuals, a la query principal
    if (is_single() && in_the_loop() && is_main_query() && get_post_type() === 'post') {

        $posts = get_posts([
            'numberposts' => 3,
            'post_type'   => 'post',
            'post_status' => 'publish',
            'orderby'     => 'date',
            'order'       => 'DESC'
        ]);

        if (!$posts) return $content;

        $extra  = '<hr>';
        $extra .= '<div class="pm-extra">';
        $extra .= '<h4>Altres entrades recents</h4><ul>';

        foreach ($posts as $p) {
            $extra .= '<li><a href="'.esc_url(get_permalink($p->ID)).'">'.esc_html(get_the_title($p->ID)).'</a></li>';
        }
        $extra .= '</ul></div>';

        return $content . $extra;
    }

    return $content;
}

/**
 * (2) Modifica el títol de les pàgines: "Pàgina {títol}" (FILTER the_title)
 */
add_filter('the_title', 'pm_modificar_titol_pagines', 10, 2);

function pm_modificar_titol_pagines($title, $post_id) {
    if (is_admin()) return $title;

    if (get_post_type($post_id) === 'page') {
        // Evita afectar menús i llocs on WP crida títols fora del loop
        if (in_the_loop() && is_main_query()) {
            return 'Pàgina ' . $title;
        }
    }
    return $title;
}

/**
 * (3) Acció automàtica quan es crea una nova entrada (ACTION wp_insert_post)
 * Envia un email a l’admin només quan és una entrada nova (no update).
 */
add_action('wp_insert_post', 'pm_accio_nova_entrada', 10, 3);

function pm_accio_nova_entrada($post_ID, $post, $update) {
    if ($update) return;
    if ($post->post_type !== 'post') return;
    if ($post->post_status !== 'publish') return;

    $admin_email = get_option('admin_email');
    $subject = 'Nova entrada creada: ' . $post->post_title;
    $message = "S'ha publicat una nova entrada.\n\nTítol: {$post->post_title}\nID: {$post_ID}\n";

    wp_mail($admin_email, $subject, $message);
}
