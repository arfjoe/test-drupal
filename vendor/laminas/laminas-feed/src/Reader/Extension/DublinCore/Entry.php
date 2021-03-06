<?php

namespace Laminas\Feed\Reader\Extension\DublinCore;

use DateTime;
use DOMNodeList;
use Laminas\Feed\Reader;
use Laminas\Feed\Reader\Collection;
use Laminas\Feed\Reader\Extension;

use function array_key_exists;
use function is_array;

class Entry extends Extension\AbstractEntry
{
    /**
     * Get an author entry
     *
     * @param  int $index
     * @return null|array<string, string>
     */
    public function getAuthor($index = 0)
    {
        $authors = $this->getAuthors();

        return isset($authors[$index]) && is_array($authors[$index])
            ? $authors[$index]
            : null;
    }

    /**
     * Get an array with feed authors
     *
     * @return array
     */
    public function getAuthors()
    {
        if (array_key_exists('authors', $this->data)) {
            return $this->data['authors'];
        }

        $authors = [];
        $list    = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc11:creator');

        if (! $list instanceof DOMNodeList || ! $list->length) {
            $list = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc10:creator');
        }

        if (! $list instanceof DOMNodeList || ! $list->length) {
            $list = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc11:publisher');

            if (! $list instanceof DOMNodeList || ! $list->length) {
                $list = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc10:publisher');
            }
        }

        if ($list instanceof DOMNodeList && $list->length) {
            foreach ($list as $author) {
                $authors[] = [
                    'name' => $author->nodeValue,
                ];
            }
            $authors = new Collection\Author(
                Reader\Reader::arrayUnique($authors)
            );
        } else {
            $authors = null;
        }

        $this->data['authors'] = $authors;

        return $this->data['authors'];
    }

    /**
     * Get categories (subjects under DC)
     *
     * @return Collection\Category
     */
    public function getCategories()
    {
        if (array_key_exists('categories', $this->data)) {
            return $this->data['categories'];
        }

        $list = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc11:subject');

        if (! $list instanceof DOMNodeList || ! $list->length) {
            $list = $this->getXpath()->evaluate($this->getXpathPrefix() . '//dc10:subject');
        }

        if ($list instanceof DOMNodeList && $list->length) {
            $categoryCollection = new Collection\Category();
            foreach ($list as $category) {
                $categoryCollection[] = [
                    'term'   => $category->nodeValue,
                    'scheme' => null,
                    'label'  => $category->nodeValue,
                ];
            }
        } else {
            $categoryCollection = new Collection\Category();
        }

        $this->data['categories'] = $categoryCollection;
        return $this->data['categories'];
    }

    /**
     * Get the entry content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getDescription();
    }

    /**
     * Get the entry description
     *
     * @return string
     */
    public function getDescription()
    {
        if (array_key_exists('description', $this->data)) {
            return $this->data['description'];
        }

        $description = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc11:description)');

        if (! $description) {
            $description = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc10:description)');
        }

        if (! $description) {
            $description = null;
        }

        $this->data['description'] = $description;

        return $this->data['description'];
    }

    /**
     * Get the entry ID
     *
     * @return string
     */
    public function getId()
    {
        if (array_key_exists('id', $this->data)) {
            return $this->data['id'];
        }

        $id = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc11:identifier)');

        if (! $id) {
            $id = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc10:identifier)');
        }

        $this->data['id'] = $id;

        return $this->data['id'];
    }

    /**
     * Get the entry title
     *
     * @return string
     */
    public function getTitle()
    {
        if (array_key_exists('title', $this->data)) {
            return $this->data['title'];
        }

        $title = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc11:title)');

        if (! $title) {
            $title = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc10:title)');
        }

        if (! $title) {
            $title = null;
        }

        $this->data['title'] = $title;

        return $this->data['title'];
    }

    /**
     * @return null|DateTime
     */
    public function getDate()
    {
        if (array_key_exists('date', $this->data)) {
            return $this->data['date'];
        }

        $d    = null;
        $date = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc11:date)');

        if (! $date) {
            $date = $this->getXpath()->evaluate('string(' . $this->getXpathPrefix() . '/dc10:date)');
        }

        if ($date) {
            $d = new DateTime($date);
        }

        $this->data['date'] = $d;

        return $this->data['date'];
    }

    /**
     * Register DC namespaces
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $this->getXpath()->registerNamespace('dc10', 'http://purl.org/dc/elements/1.0/');
        $this->getXpath()->registerNamespace('dc11', 'http://purl.org/dc/elements/1.1/');
    }
}
