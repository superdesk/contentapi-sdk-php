<?php

/**
 * This file is part of the PHP SDK library for the Superdesk Content API.
 *
 * Copyright 2015 Sourcefabric z.u. and contributors.
 *
 * For the full copyright and license information, please see the
 * AUTHORS and LICENSE files distributed with this source code.
 *
 * @copyright 2015 Sourcefabric z.ú.
 * @license http://www.superdesk.org/license
 */

namespace spec\Superdesk\ContentApiSdk;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\Client\ClientInterface;
use Superdesk\ContentApiSdk\ContentApiSdk;

class ContentApiSdkSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\ContentApiSdk');
    }

    function let(ClientInterface $client)
    {
        $client->beADoubleOf('Superdesk\ContentApiSdk\Client\FileGetContentsClient');
        $this->beConstructedWith($client);
    }

    function its_method_get_item_should_return_an_item($client)
    {
        $client->makeApiCall(Argument::Any(), null, null)->willReturn('{ "version": "v3", "renditions": { "main": { "height": 720, "title": "Cat Full Resolution", "href": "http:/pictures.com/photos/cat-big.jpg", "width": 1050, "mimetype": "image/jpg" }, "small": { "height": 180, "title": "Cat Low Resolution", "href": "http:/pictures.com/photos/cat-small.jpg", "width": 262, "mimetype": "image/jpg" } }, "pubstatus": "usable", "urgency": 9, "type": "picture", "located": "Some backyard in one of the many Berlin houses", "description_html": "<p>This is a <em>cute</em> picture of our cat.</p>", "_links": { "parent": { "title": "home", "href": "/" }, "collection": { "title": "items", "href": "items" }, "self": { "title": "Item", "href": "items/tag:demodata.org,0001:ninjs_XYZ123" } }, "description_text": "This is a cute picture of our cat.", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0001%3Aninjs_XYZ123", "place": [ { "name": "my neighbour\'s backyard" }, { "name": "Berlin" }, { "name": "Germany" } ], "headline": "Amazing! A very cute fluffy cat!", "versioncreated": "2015-03-16T08:12:00+0000", "byline": "Andy Catlover", "usageterms": "You can use this photo in any way you want." }');
        $this->getItem(Argument::any())->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Item');
    }

    function its_method_get_item_should_return_an_exception_on_invalid_data($client)
    {
        $client->makeApiCall(Argument::Any(), null, null)->willReturn(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetItem(Argument::any());

        $client->makeApiCall(Argument::Any(), null, null)->willReturn('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>Invalid response</error>');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetItem(Argument::any());
    }

    function its_method_get_items_should_return_null_for_no_items($client)
    {
        $parameters = array('start_date' => '1970-01-01');
        $client->makeApiCall('/items', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->getItems($parameters)->shouldBe(null);
    }

    function its_method_get_items_should_return_items_for_items($client)
    {
        $parameters = array('start_date' => '1970-01-01');
        $client->makeApiCall('/items', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-01-01" } }, "_items": [ { "version": "v3", "renditions": { "main": { "height": 720, "title": "Cat Full Resolution", "href": "http:/pictures.com/photos/cat-big.jpg", "width": 1050, "mimetype": "image/jpg" }, "small": { "height": 180, "title": "Cat Low Resolution", "href": "http:/pictures.com/photos/cat-small.jpg", "width": 262, "mimetype": "image/jpg" } }, "pubstatus": "usable", "urgency": 9, "type": "picture", "located": "Some backyard in one of the many Berlin houses", "description_html": "<p>This is a <em>cute</em> picture of our cat.</p>", "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0001:ninjs_XYZ123" } }, "description_text": "This is a cute picture of our cat.", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0001%3Aninjs_XYZ123", "place": [ { "name": "my neighbour\'s backyard" }, { "name": "Berlin" }, { "name": "Germany" } ], "headline": "Amazing! A very cute fluffy cat!", "versioncreated": "2015-03-16T08:12:00+0000", "byline": "Andy Catlover", "usageterms": "You can use this photo in any way you want." }, { "version": "v1", "person": [ { "name": "John McWriter", "rel": "author" } ], "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0003:ninjs_XYZ123" } }, "body_text": "LONDON, UK (DD.org) -- Everybody happily celebrating.", "profile": "text-only", "copyrightnotice": "Copyright 2015 Santa & Friends - Free to use for children.", "pubstatus": "usable", "language": "en", "type": "text", "located": "London, UK, Europe", "subject": [ { "name": "London", "rel": "about" }, { "name": "Holidays", "rel": "about" }, { "name": "Entertainment", "rel": "about" } ], "urgency": 8, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123", "headline": "People in London celebrate New Year", "versioncreated": "2015-01-01T00:00:01+0000", "body_html": "<p>LONDON, UK (DD.org) -- Everybody happily celebrating.</p>", "byline": "Santa Claus and John McWriter", "place": [ { "name": "London" }, { "name": "UK" }, { "name": "Europe" } ] }, { "version": "v2", "person": [ { "name": "John McWriter", "rel": "author" } ], "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0004:ninjs_XYZ123" } }, "body_text": "LONDON, UK (DD.org) -- Everybody is celebrating.", "profile": "text-only", "copyrightnotice": "Copyright 2015 Santa & Friends - Free to use for children.", "pubstatus": "usable", "language": "en", "type": "text", "located": "London, UK, Europe", "subject": [ { "name": "London", "rel": "about" }, { "name": "Holidays", "rel": "about" }, { "name": "Entertainment", "rel": "about" } ], "urgency": 6, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0004%3Aninjs_XYZ123", "headline": "People in UK celebrate New Year", "versioncreated": "2015-01-01T01:25:08+0000", "body_html": "<p>LONDON, UK (DD.org) -- Everybody is celebrating.</p>", "byline": "Santa Claus and John McWriter", "place": [ { "name": "London" }, { "name": "UK" }, { "name": "Europe" } ] }, { "version": "v1", "person": [ { "name": "Donald Banks", "rel": "author" } ], "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0005:ninjs_XYZ123" } }, "body_text": "PARIS, FR -- At the press conference, there was...", "profile": "text-only", "copyrightnotice": "Copyright 2015 DemoData ltd., All Rights Reserved.", "pubstatus": "usable", "language": "en", "type": "text", "located": "Paris city center, France", "subject": [ { "name": "France", "rel": "about" }, { "name": "Politics", "rel": "about" }, { "name": "Daily news", "rel": "about" } ], "urgency": 5, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0005%3Aninjs_XYZ123", "headline": "Hollande proposes a new law", "versioncreated": "2015-01-31T12:41:55+0000", "body_html": "<p>PARIS, FR -- At the press conference, there was...</p>", "byline": "Donald Banks", "place": [ { "name": "Paris" }, { "name": "France" } ] }, { "version": "v1", "person": [ { "name": "Mr. Ice", "rel": "author" } ], "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0008:ninjs_XYZ123" } }, "body_text": "OSTRAVA, CZ -- The result was 5:1.", "profile": "text-only", "copyrightnotice": "Free for non-commercial use", "pubstatus": "usable", "language": "en", "type": "text", "located": "Ostrava Ice Hockey Hall", "subject": [ { "name": "Sports", "rel": "about" }, { "name": "Hockey", "rel": "about" } ], "urgency": 9, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0008%3Aninjs_XYZ123", "headline": "USA beats the Finns", "versioncreated": "2015-05-02T17:12:39+0000", "body_html": "<p>OSTRAVA, CZ -- The result was 5:1.</p>", "byline": "Mr. Ice", "place": [ { "name": "Ostrava" }, { "name": "Czech Republic" } ] }, { "version": "v1", "person": [ { "name": "Peter Lamut", "rel": "author" } ], "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0009:ninjs_XYZ123" } }, "description_html": "<p>The goal is to provide sample items &amp; packages for testing</p>", "description_text": "The goal is to provide sample items & packages for testing", "body_text": "This body text describes the whole thing.", "profile": "text-only", "copyrightnotice": "Ask Surcefabrcic z.ú. for permission", "pubstatus": "usable", "language": "en", "type": "text", "located": "Croatian coast", "subject": [ { "name": "Tourism", "rel": "about" } ], "urgency": 9, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0009%3Aninjs_XYZ123", "headline": "API demo content under development", "versioncreated": "2015-05-20T22:19:10+0000", "body_html": "<p>This body text describes the whole thing.</p>", "byline": "Peter Lamut", "place": [ { "name": "Rijeka" }, { "name": "Croatia" } ] }, { "version": "v1", "_links": { "self": { "title": "Item", "href": "items/tag:demodata.org,0010:ninjs_XYZ123" } }, "description_text": "V gostilni pod Rožnikom so spekli okusne čevapčiče", "body_text": "Kosilo je bilo pripravljeno v 10-ih minutah in to je to.", "profile": "text-only", "language": "sl", "pubstatus": "usable", "person": [ { "name": "Peter Lamut", "rel": "author" } ], "type": "text", "located": "V glavnem mestu Slovenije", "subject": [ { "name": "Food", "rel": "about" }, { "name": "Foreign News", "rel": "about" } ], "urgency": 9, "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0010%3Aninjs_XYZ123", "headline": "Peter je šel na odlično kosilo", "versioncreated": "2015-03-21T12:05:49+0000", "body_html": "<p>Kosilo je bilo pripravljeno v 10-ih minutah in to je to.</p>", "byline": "avtor prispevka je Peter Lamut", "place": [ { "name": "Ljubljana" }, { "name": "Slovenija" } ] } ], "_meta": { "page": 1, "total": 7, "max_results": 25 }}');

        $array = $this->getItems($parameters);
        $array->shouldBeArray();
        foreach ($array as $item) {
            $item->shouldHaveType('Superdesk\ContentApiSdk\Data\Item');
        }
    }

    function its_method_get_items_should_return_an_exception_for_invalid_data($client)
    {
        $parameters = array('start_date' => '1970-01-01');

        $client->makeApiCall('/items', $parameters, null)->willReturn(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetItems($parameters);

        $client->makeApiCall('/items', $parameters, null)->willReturn('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>Invalid response</error>');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetItems($parameters);

        $client->makeApiCall('/items', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "items", "href": "items?start_date=2015-08-01" } }, "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetItems($parameters);
    }

    function its_method_get_package_should_return_a_package($client)
    {
        $client->makeApiCall(Argument::Any(), null, null)->willReturn('{ "version": "v1", "associations": { "mainpic": { "type": "picture", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0001%3Aninjs_XYZ123" }, "storyText": { "type": "text", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123" } }, "person": [ { "name": "Hulk Hogan", "rel": "author" }, { "name": "The Phototaker", "rel": "photographer" } ], "_links": { "parent": { "title": "home", "href": "/" }, "collection": { "title": "packages", "href": "packages" }, "self": { "title": "Package", "href": "packages/tag:demodata.org,0012:ninjs_XYZ123" } }, "body_text": "Want to try McRide or McGhostHouse?", "profile": "text-photo", "copyrightnotice": "Free to use for everyone", "pubstatus": "usable", "language": "en", "type": "composite", "located": "Bilbao city center, Spain", "subject": [ { "name": "Business", "rel": "about" }, { "name": "Economy", "rel": "about" }, { "name": "Entertainment", "rel": "about" } ], "urgency": 8, "uri": "http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123", "headline": "McDonalds opens its own theme park", "versioncreated": "2014-08-15T11:33:42+0000", "body_html": "<p>Want to try <em>McRide</em> or <em>McGhostHouse</em>?</p>", "byline": "Hulk Hogan, The Undertaker", "place": [ { "name": "Bilbao" }, { "name": "Spain" } ] }');
        $this->getPackage(Argument::any())->shouldReturnAnInstanceOf('Superdesk\ContentApiSdk\Data\Package');
    }

    function its_method_get_package_should_return_an_exception_on_invalid_data($client)
    {
        $client->makeApiCall(Argument::Any(), null, null)->willReturn(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetPackage(Argument::any());

        $client->makeApiCall(Argument::Any(), null, null)->willReturn('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>Invalid response</error>');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetPackage(Argument::any());
    }

    function its_method_get_packages_should_return_null_for_no_packages($client)
    {
        $parameters = array('start_date' => '1970-01-01');
        $client->makeApiCall('/packages', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "packages", "href": "packages?start_date=2015-08-10" } }, "_items": [], "_meta": { "page": 1, "total": 0, "max_results": 25 } }');

        $this->getPackages($parameters)->shouldBe(null);
    }

    function its_method_get_packages_should_return_packges_for_packages($client)
    {
        $parameters = array('start_date' => '1970-01-01');
        $client->makeApiCall('/packages', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/" }, "self": { "title": "packages", "href": "packages?start_date=1970-01-01" } }, "_items": [ { "version": "v1", "associations": { "videoFootage": { "type": "video", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0002%3Aninjs_XYZ123" }, "relatedStory": { "type": "composite", "uri": "http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123" } }, "person": [ { "name": "Vito Corleone", "rel": "about" }, { "name": "Franco di Banco", "rel": "author" }, { "name": "Photo Grapher jr.", "rel": "photographer" } ], "_links": { "self": { "title": "Package", "href": "packages/tag:demodata.org,0011:ninjs_XYZ123" } }, "body_text": "Reporter: \"Don Vito Corleone, bongiorno!\", etc.", "profile": "rich-interlinked-story", "copyrightnotice": "Licensed under MIT public license", "pubstatus": "usable", "language": "en", "type": "composite", "located": "Naples, Italy", "subject": [ { "name": "Celebrities", "rel": "about" }, { "name": "Interviews", "rel": "about" } ], "urgency": 4, "uri": "http://publicapi:5050/packages/tag%3Ademodata.org%2C0011%3Aninjs_XYZ123", "headline": "Exclusive interview with the great mafia boss", "versioncreated": "2015-05-03T07:48:51+0000", "body_html": "<b>Reporter:</b> &quot;Don Vito Corleone, bongiorno!&quot;, etc.", "byline": "Franco di Banco, Photo Grapher jr. and an anonymous mafia source", "place": [ { "name": "Naples" }, { "name": "Italy" } ] }, { "version": "v1", "associations": { "mainpic": { "type": "picture", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0001%3Aninjs_XYZ123" }, "storyText": { "type": "text", "uri": "http://publicapi:5050/items/tag%3Ademodata.org%2C0003%3Aninjs_XYZ123" } }, "person": [ { "name": "Hulk Hogan", "rel": "author" }, { "name": "The Phototaker", "rel": "photographer" } ], "_links": { "self": { "title": "Package", "href": "packages/tag:demodata.org,0012:ninjs_XYZ123" } }, "body_text": "Want to try McRide or McGhostHouse?", "profile": "text-photo", "copyrightnotice": "Free to use for everyone", "pubstatus": "usable", "language": "en", "type": "composite", "located": "Bilbao city center, Spain", "subject": [ { "name": "Business", "rel": "about" }, { "name": "Economy", "rel": "about" }, { "name": "Entertainment", "rel": "about" } ], "urgency": 8, "uri": "http://publicapi:5050/packages/tag%3Ademodata.org%2C0012%3Aninjs_XYZ123", "headline": "McDonalds opens its own theme park", "versioncreated": "2014-08-15T11:33:42+0000", "body_html": "<p>Want to try <em>McRide</em> or <em>McGhostHouse</em>?</p>", "byline": "Hulk Hogan, The Undertaker", "place": [ { "name": "Bilbao" }, { "name": "Spain" } ] } ], "_meta": { "page": 1, "total": 2, "max_results": 25 } }');

        $array = $this->getPackages($parameters);
        $array->shouldBeArray();
        foreach ($array as $package) {
            $package->shouldHaveType('Superdesk\ContentApiSdk\Data\Package');
        }
    }

    function its_method_get_packages_should_return_an_exception_for_invalid_data($client)
    {
        $parameters = array('start_date' => '1970-01-01');

        $client->makeApiCall('/packages', $parameters, null)->willReturn(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetPackages($parameters);

        $client->makeApiCall('/packages', $parameters, null)->willReturn('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>Invalid response</error>');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetPackages($parameters);

        $client->makeApiCall('/packages', $parameters, null)->willReturn('{ "_links": { "parent": { "title": "home", "href": "/i" }, "self": { "title": "packages", "href": "packages?start_date=2015-08-10" } }, "_meta": { "page": 1, "total": 0, "max_results": 25 } }');
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetPackages($parameters);
    }

    function its_method_get_available_endpoints_should_contain_all_endpoints()
    {
        $endpoints = $this->getAvailableEndpoints();
        $endpoints->shouldBeArray();
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_ITEMS);
        $endpoints->shouldContain(ContentApiSdk::SUPERDESK_ENDPOINT_PACKAGES);
    }

    function its_method_get_valid_parameters_should_return_valid_parameters()
    {
        $validParameters = $this->getValidParameters();
        $validParameters->shouldBe(ContentApiSdk::$validParameters);
    }

    function its_method_process_parameters_should_properly_process()
    {
        $parameters = array('start_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('start_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'start_date' => $parameters['start_date']->format('Y-m-d')
            ));

        $parameters = array('end_date' => '2015-01-01');
        $this->processParameters($parameters)->shouldBe($parameters);
        $parameters = array('end_date' => new \DateTime());
        $this->processParameters($parameters)
            ->shouldBe(array(
                'end_date' => $parameters['end_date']->format('Y-m-d')
            ));

        $parameters = array('q' => 'test');
        $this->processParameters($parameters)->shouldBe($parameters);

        $parameters = array('include_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'include_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'include_fields' => 'headline,body_text'
            ));

        $parameters = array('exclude_fields' => 'headline,body_text');
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'exclude_fields' => array('headline', 'body_text')
            ))
            ->shouldBe(array(
                'exclude_fields' => 'headline,body_text'
            ));

        $parameters = array('page' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'page' => '2'
            ))
            ->shouldBe(array(
                'page' => 2
            ));

        $parameters = array('max_results' => 2);
        $this->processParameters($parameters)->shouldBe($parameters);
        $this->processParameters(array(
                'max_results' => '2'
            ))
            ->shouldBe(array(
                'max_results' => 2
            ));
    }

    function its_method_process_parameters_should_throw_exceptions()
    {
        $parameters = array(
            'start_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'start_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'end_date' => '01-01-1970',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'q' => array(),
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'include_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'exclude_fields' => 1,
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'page' => 'page',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);

        $parameters = array(
            'max_results' => 'max_results',
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidArgumentException')->duringProcessParameters($parameters);
    }

    function its_method_process_parameters_should_validate()
    {
        $validKeys = $this->getValidParameters();

        $invalidParameters = array(
            'some_name' => 'some value',
            'another_name' => 'another value',
            'max_results' => '5'
        );
        $processedParameters = $this->processParameters($invalidParameters, true);
        $processedParameters->shouldBe(array('max_results' => 5));
    }

    function its_method_get_valid_json_obj_should_return_an_object_on_succes()
    {
        $jsonObj = self::getValidJsonObj('{ "some key" : "some value" }');
        $jsonObj->shouldHaveType('stdClass');
    }

    function its_method_get_valid_json_obj_should_throw_an_exception_during_failure()
    {
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj(null);
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj('<?xml version="1.0" encoding="UTF-8" standalone="no" ?><error>This is not json</error>');

        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\InvalidDataException')->duringGetValidJsonObj('{ "some key" : "some value }');
    }
}
