<?php 
/**
 * post helper functions
 */

/**
 * Add custom field for rest post api
 */
add_action('rest_api_init', function () {
    // author_full
    register_rest_field('post', 'author_full', [
        'get_callback' => function ($post) {
          // get author full data (fullname, bio, avatar, email, url, etc)
          $author = get_user_by('ID', $post['author']);
          return [
            'fullname' => $author->display_name,
            'bio' => $author->description,
            'avatar' => get_avatar_url($author->ID),
            'email' => $author->user_email,
          ];
        },
        'schema' => [
          'description' => 'Author data',
          'type' => 'array',
        ],
    ]);

    // categories full 
    register_rest_field('post', 'categories_full', [
      'get_callback' => function ($post) {
        $categories = get_the_category($post['id']);
        return $categories;
      },
      'schema' => [
        'description' => 'Categories',
        'type' => 'array',
      ],
    ]);

    // tags full
    register_rest_field('post', 'tags_full', [
      'get_callback' => function ($post) {
        $tags = get_the_tags($post['id']);
        return $tags;
      },
      'schema' => [
        'description' => 'Tags',
        'type' => 'array',
      ],
    ]);
});