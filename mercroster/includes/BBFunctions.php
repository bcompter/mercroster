<?php
class BBFunctions
{
  /**
   * This function is used to change "board tags" to html tags in text
   * @param <string> $text
   * @return <string>
   */
  function addTags($text)
  {
    $search = array(
    '/\[b\](.*?)\[\/b\]/is',
    '/\[i\](.*?)\[\/i\]/is',
    '/\[u\](.*?)\[\/u\]/is',
    '/\[q\](.*?)\[\/q\]/is',
    '/\[h1\](.*?)\[\/h1\]/is',
    '/\[h2\](.*?)\[\/h2\]/is',
    '/\[h3\](.*?)\[\/h3\]/is',
    '/\[h4\](.*?)\[\/h4\]/is',
    '/\[h5\](.*?)\[\/h5\]/is',
    '/\[h6\](.*?)\[\/h6\]/is',
    '/\[img\](.*?)\[\/img\]/is',
    '/\[url\](.*?)\[\/url\]/is',
    '/\[url\=(.*?)\](.*?)\[\/url\]/is',
    '/\[quote\](.*?)\[\/quote\]/is',
    '/\[quote author\=(.*?)\ link\=(.*?) date\=(.*?)\](.*?)/is',
    '/\[quote author\=(.*?)\ link\=(.*?) date\=(.*?)\](.*?)/is',
    '/\[\/quote\]/is',
    '/\[hr\]/is'
    );

    $replace = array(
    '<strong>$1</strong>',
    '<em>$1</em>',
    '<span class="underline">$1</span>',
    '<q>$1</q>',
    '<h1>$1</h1>',
    '<h2>$1</h2>',
    '<h3>$1</h3>',
    '<h4>$1</h4>',
    '<h5>$1</h5>',
    '<h6>$1</h6>',
    '<div class="image"><img src="$1" alt="image" /></div>',
    '<a href="$1">$1</a>',
    '<a href="$1">$2</a>',
    '<div class="quotetopic">Quote:</div><div class="quote">$1</div>',
    '<div class="quotetopic"><a class="quotetopic" href="$2">Quote from: $1 on $3</a></div><div class="quote">$4',
    '<div class="quotetopic"><a class="quotetopic" href="$2">Quote from: $1 on $3</a></div><div class="quote">$4',
    '</div>',
    '<hr />'
    );
    $text=preg_replace($search, $replace, $text);
    return $text;
  }

  /**
   * This function is used to change "board tags" to html tags in text
   * @param <string> $text
   * @return <string>
   */
  function removeTags($text)
  {
    $search = array(
    '/\[b\](.*?)\[\/b\]/is',
    '/\[i\](.*?)\[\/i\]/is',
    '/\[u\](.*?)\[\/u\]/is',
    '/\[q\](.*?)\[\/q\]/is',
    '/\[h1\](.*?)\[\/h1\]/is',
    '/\[h2\](.*?)\[\/h2\]/is',
    '/\[h3\](.*?)\[\/h3\]/is',
    '/\[h4\](.*?)\[\/h4\]/is',
    '/\[h5\](.*?)\[\/h5\]/is',
    '/\[h6\](.*?)\[\/h6\]/is',
    '/\[img\](.*?)\[\/img\]/is',
    '/\[url\](.*?)\[\/url\]/is',
    '/\[url\=(.*?)\](.*?)\[\/url\]/is',
    '/\[quote\](.*?)\[\/quote\]/is',
    '/\[quote author\=(.*?)\ link\=(.*?) date\=(.*?)\](.*?)\[\/quote\]/is',
    '/\[hr\]/is'
    );

    $replace = array(
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '$1',
    '',
    '',
    '',
    '',
    '',
    ''
    );
    $text=preg_replace ($search, $replace, $text);
    return $text;
  }

}
?>