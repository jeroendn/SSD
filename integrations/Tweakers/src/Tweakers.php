<?php

namespace SSD\Integrations\Tweakers;

final class Tweakers
{
    private const string URL = 'https://tweakers.net/feeds/mixed.xml';

    /** @var array<Post> */
    private array $posts = [];

    public function __construct()
    {
        if (!($x = simplexml_load_file(self::URL)))
            return [];

        $this->posts = [];

        foreach ($x->channel->item as $item) {
            $post              = new Post();
            $post->date        = (string)$item->pubDate;
            $post->timestamp   = strtotime($item->pubDate);
            $post->url         = (string)$item->link;
            $post->title       = (string)$item->title;
            $post->description = (string)$item->description;

            $this->posts[] = $post;
        }

        return $this->posts;
    }

    /**
     * @return array<Post>
     */
    public function getLast10Items(): array
    {
        return array_chunk($this->posts, 10)[0];
    }
}