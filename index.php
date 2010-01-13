<?php

require_once('./tao/require.php');

Page('xhtml');

Page()->setTitle('Tag As Object - Demo');

Page()->addCss('static/css/dummy.css');
Page()->addScriptSrc('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js', 'top');

Page()->addMeta('keywords', 'asp... no kidding, php5');
Page()->addHTTPMeta('expires', 'Sat, 01 Dec 2001 00:00:00 GMT');
Page()->setBase('./');


$title = tao('<h1 />')
			->setId('myTitle')
			->setStyle( array(
						'text-decoration'=>'underline',
						'font-family'=>'Arial, MS Trebuchet, sans-serif;'
						) )
			->addStyle( array(
						'background-color: #CECECE',
						'padding: .5em'
						) )
			->addContent('Tag As Object');
Page()->addContent($title);

tao('<hr />')->addTo( Page() );

tao('#myTitle')
	->removeStyle('text-decoration')
	->addStyle('color: #516b85');

$second_link = tao('<a />')->setAttribute('href', 'http://www.php.net')->addContent('PHP');

tao('<a />')
	->setAttribute('href', 'http://github.com/loranger/tao')
	->addContent('tao was here')
	->addClass('test')
	->addTo( Page() );

Page()->addContent( $second_link );

$bold = tao('<strong id="import">empty</strong>');
$bold->addContent(" or not empty...");
Page()->addContent($bold);

Page()->find('a')->addStyle('font-size: 1.5em; color: #856b51');

tao('#import')->setStyle('display: block');

?>