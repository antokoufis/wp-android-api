<?php

/**
 * Plugin Name:       Custom Android Wordpress API
 * Plugin URI:        https://antonisk.com
 * Description:       A plugin that create custom WP Api Custom Endpoints
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Antonis Koufis
 * Text Domain:       wp-android-api
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

add_action("rest_api_init", function () {
    register_rest_route("api", "homepage_api", [
        "methods" => "GET",
        "callback" => "homepage_function",
    ]);
});

function homepage_function($data)
{
    $postData = new WP_Query([
        "post_type" => "post",
        "post_status" => "publish",
        "order_by" => "DATE",
        "cat" => isset($data["cat"]) ? $data["cat"] : "",
        "order" => "DESC",
        "posts_per_page" => isset($data["per_page"]) ? $data["per_page"] : 500,
        "paged" => isset($data["page"]) ? $data["page"] : 1,
    ]);

    $dataArray = [];

    foreach ($postData->posts as $post) {
        $dIA = [];

        $dIA["pid"] = $post->ID;
        $dIA["post_date"] = $post->post_date;
        $dIA["title"] = $post->post_title;
        $dIA["url"] = $post->guid;
        $dIA["post_content"] = substr($post->post_content, 0, 120);

        $post_categories = wp_get_post_categories($post->ID);
        $cats = [];

        foreach ($post_categories as $c) {
            $cat = get_category($c);
            $cats[] = $cat->name;
        }

        $dIA["categories"] = implode(", ", $cats);

        $image = get_the_post_thumbnail_url($post);
        if (!$image) {
            $image = "";
        }

        $dIA["image"] = $image;

        $dataArray[] = $dIA;
    }

    $response["News"] = $dataArray;

    return $response;
}

//Fetch News by ID

add_action("rest_api_init", function () {
    register_rest_route("api", "news_by_id", [
        "methods" => "GET",
        "callback" => "news_by_id",
    ]);
});

function news_by_id($data)
{
    $postData = new WP_Query([
        "p" => isset($data["id"]) ? $data["id"] : 1,
        "post_type" => "post",
        "post_status" => "publish",
        "order_by" => "DATE",
        "order" => "DESC",
        "post_per_page" => isset($data["posts"]) ? $data["posts"] : 15,
        "paged" => isset($data["posts"]) ? $data["posts"] : 1,
    ]);

    $dataArray = [];

    foreach ($postData->posts as $post) {
        $dIA = [];

        $dIA["pid"] = $post->ID;
        $dIA["post_date"] = $post->post_date;
        $dIA["title"] = $post->post_title;
        $dIA["url"] = $post->guid;
        $dIA["post_content"] = $post->post_content;

        $post_categories = wp_get_post_categories($post->ID);
        $cats = [];

        foreach ($post_categories as $c) {
            $cat = get_category($c);
            $cats[] = $cat->name;
        }

        $dIA["categories"] = implode(", ", $cats);

        $image = get_the_post_thumbnail_url($post);
        if (!$image) {
            $image = "";
        }

        $dIA["image"] = $image;

        $dataArray[] = $dIA;
    }

    $response["News"] = $dataArray;

    return $response;
}

add_action("rest_api_init", function () {
    register_rest_route("api", "news_by_ids", [
        "methods" => "GET",
        "callback" => "news_by_ids",
    ]);
});

function news_by_ids($data)
{
    $postData = new WP_Query([
        "post__in" => explode(",", $data["ids"]),
        "post_type" => "post",
        "post_status" => "publish",
        "order_by" => "DATE",
        "order" => "DESC",
        "post_per_page" => isset($data["posts"]) ? $data["posts"] : 15,
        "paged" => isset($data["posts"]) ? $data["posts"] : 1,
    ]);

    $dataArray = [];

    foreach ($postData->posts as $post) {
        $dIA = [];

        $dIA["pid"] = $post->ID;
        $dIA["post_date"] = $post->post_date;
        $dIA["title"] = $post->post_title;
        $dIA["url"] = $post->guid;
        $dIA["post_content"] = substr($post->post_content, 0, 120);

        $post_categories = wp_get_post_categories($post->ID);
        $cats = [];

        foreach ($post_categories as $c) {
            $cat = get_category($c);
            $cats[] = $cat->name;
        }

        $dIA["categories"] = implode(", ", $cats);

        $image = get_the_post_thumbnail_url($post);
        if (!$image) {
            $image = "";
        }

        $dIA["image"] = $image;

        $dataArray[] = $dIA;
    }

    $response["News"] = $dataArray;

    return $response;
}
