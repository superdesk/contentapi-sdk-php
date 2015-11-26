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

namespace spec\Superdesk\ContentApiSdk\API;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Superdesk\ContentApiSdk\ContentApiSdk;
use Superdesk\ContentApiSdk\API\Response;

class ResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Superdesk\ContentApiSdk\API\Response');
    }

    function let()
    {
        $this->beConstructedWith(
            '{"_meta": {"max_results": 2, "total": 45, "page": 3}, "_links": {"self": {"title": "packages", "href": "packages?start_date=2015-01-01&max_results=2&page=3"}, "parent": {"title": "home", "href": "/"}, "next": {"title": "next page", "href": "packages?max_results=2&page=4"}, "prev": {"title": "previous page", "href": "packages?max_results=2&page=2"}, "last": {"title": "last page", "href": "packages?max_results=2&page=23"}}, "_items": [{"body_html": "<p>SAUDI-LED COALITION SPOKESMAN SAYS COALITION JETS WERE IN ACTION\nIN SAADA GOVERNORATE, BUT NOT INSIDE CITY</p>", "pubstatus": "usable", "subject": [{"qcode": "16009000", "name": "war"}, {"qcode": "11002000", "name": "diplomacy"}, {"qcode": "10000000", "name": "lifestyle and leisure"}, {"qcode": "11000000", "name": "politics"}, {"qcode": "16000000", "name": "unrest, conflicts and  war"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-27T17%3A53%3A17.408024%3Aa53e9fb0-d43e-41a4-a767-0dddaa549842", "headline": "SAUDI-LED COALITION SPOKESMAN SAYS CO", "priority": 1, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/tag%3Alocalhost%3A2015%3A120dfa7c-62eb-40c4-8972-7e0d41e3c220", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-27T17:53:17.408024:a53e9fb0-d43e-41a4-a767-0dddaa549842"}}, "urgency": 1, "language": "en", "versioncreated": "2015-10-27T17:53:17+0000", "byline": "mkk", "located": "Santa Fe", "type": "composite"}, {"body_html": "<p>NAREG DARWIN</p>", "pubstatus": "usable", "subject": [{"qcode": "01001000", "parent": "01000000", "name": "archaeology"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-27T21%3A26%3A35.366266%3Acbdb917d-5e5e-4796-b384-c784baa766d2", "headline": "NAREG DARWIN", "priority": 1, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-10-27T21%3A26%3A07.748901%3A49745a57-04d8-4645-8e63-b3a06f2ca36b", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-27T21:26:35.366266:cbdb917d-5e5e-4796-b384-c784baa766d2"}}, "urgency": 1, "language": "en", "versioncreated": "2015-10-27T21:26:35+0000", "byline": "N", "located": "Holdfast Bay", "type": "composite"}]}',
            array(
                'Connection' => 'keep-alive',
                'Content-Encoding' => 'gzip',
                'Content-Type' => 'application/json',
                'Date' => 'Wed, 11 Nov 2015 13:56:14 GMT',
                'Last-Modified' => 'Mon, 02 Nov 2015 21:15:26 GMT',
                'Server' => 'nginx',
                'Transfer-Encoding' => 'chunked',
                'Vary' => 'Accept-Encoding, Accept-Encoding',
                'X-Total-Count' => '45'
            )
        );
    }

    function it_should_support_json_content_type_via_header()
    {
        $this->getContentType()->shouldBe(Response::CONTENT_TYPE_JSON);
    }

    function it_should_support_json_content_type_via_content_snooping()
    {
        $this->beConstructedWith(
            '{"_meta": {"max_results": 1, "total": 45, "page": 1}, "_links": {"self": {"title": "packages", "href": "packages?start_date=2015-01-01&max_results=1"}, "parent": {"title": "home", "href": "/"}, "next": {"title": "next page", "href": "packages?max_results=1&page=2"}, "last": {"title": "last page", "href": "packages?max_results=1&page=45"}}, "_items": [{"headline": "Reuters Sports News Summary", "pubstatus": "usable", "type": "composite", "body_html": "<p>Following is a summary of current sports news briefs.</p>\n<p>Serena to start 2016 at Hopman Cup in Perth</p>\n<p>World number one Serena Williams will again launch her\nseason at the Hopman Cup in Australia, tournament organizers\nsaid on Wednesday. Williams reached the final of the mixed team\nevent last year with American compatriot John Isner to kick off\na superb season where she won the Australian and French Opens\nand Wimbledon to take her grand slam singles haul to 21.</p>\n<p>In a series hole, Cubs vow to keep swinging</p>\n<p>History has not often been kind to the Cubs, Chicago\'s\npopular but seldom successful north side baseball franchise, but\nthe young team remains hopeful they can continue their\npost-season run. The Cubs survived a one-game playoff to earn a\nspot in the National League\'s divisional series, where they\ndispatched the arch rival St. Louis Cardinals.</p>\n<p>NBA player accuses Milwaukee-area jeweler of racial\nprofiling</p>\n<p>Milwaukee Bucks forward John Henson has accused a\nMilwaukee-area jeweler of racial profiling after its employees\nlocked him out and called police when he went to the store to\nbuy a watch on Monday afternoon. The president of\nSchwanke-Kasten Jewelers issue a statement Monday night\napologizing for the incident at the store in Whitefish Bay, a\nnorthern suburb of Milwaukee.</p>\n<p>Royals rout Jays, one win from World Series berth</p>\n<p>The Kansas City Royals pounced early then powered their way\nto a 14-2 demolition of the Toronto Blue Jays on Tuesday to move\none win away from reaching the World Series for a second\nconsecutive year. The Royals, with the help of a two-run homer\nfrom Ben Zobrist, scored four runs in the first inning then\nsealed the victory with a four-run seventh to seize a commanding\n3-1 lead in the best-of-seven American League Championship\nSeries.</p>\n<p>Mets beat Cubs, one win away from World Series</p>\n<p>The New York Mets snapped a 2-2 tie on a wild-pitch\nstrikeout  on the way to beating the Chicago Cubs 5-2 on\nTuesday, putting them one win away from the World Series. The\nvictory, keyed by the strong pitching of Jacob deGrom and\nanother home run by Daniel Murphy, gave New York a 3-0 lead in\nthe best-of-seven National League Championship Series.</p>\n<p>Yankees\' Tanaka has elbow surgery, will return in 2016</p>\n<p>Japanese right-hander Masahiro Tanaka had surgery on Tuesday\nto remove a bone spur in his pitching elbow and is expected to\nbe ready for the 2016 Major League Baseball season, the New York\nYankees said. The bone spur was pre-existing and dated back to\nTanaka\'s stellar pitching career in his native Japan, according\nto the Yankees.</p>\n<p>Blackhawks\' Keith out 4-6 weeks after knee surgery</p>\n<p>Three-times Stanley Cup-winning Chicago Blackhawks\ndefenseman Duncan Keith is expected to be sidelined between four\nand six weeks after having knee surgery on Tuesday, the National\nHockey League team said. Keith, a three-times All-Star who won\nOlympic gold with Canada at both the 2010 and 2014 Winter Games,\nhad a procedure to repair a meniscal tear in his right knee.</p>\n<p>Lamar Odom making \'incredible strides,\' Khloe Kardashian\nsays</p>\n<p>Reality TV star Khloe Kardashian said on Tuesday her\nestranged husband Lamar Odom has made \"incredible strides\" as he\nrecovers from a collapse in a Las Vegas brothel last week, while\nthe former NBA player\'s aunt called his progress \"miraculous.\"\nBreaking her silence, Kardashian also thanked fans and staff at\nthe Sunrise Hospital in Las Vegas for their support during what\nshe called \"an incredibly difficult\" week.</p>\n<p>NY federal prosecutor probes daily fantasy sports business:\nWSJ</p>\n<p>Manhattan Attorney Preet Bharara is investigating whether\nthe business model behind daily fantasy sports companies such as\nDraftKings Inc and FanDuel Inc violates federal law, the Wall\nStreet Journal reported. The investigation is at an early stage\nand senior Justice Department lawyers in Washington are\nundecided on whether daily fantasy sports betting violates\nfederal gambling statutes, the newspaper reported, citing people\nfamiliar with the matter.</p>\n<p>Royal Rios gives Blue Jays fans more reason to boo</p>\n<p>Former Blue Jay Alex Rios has been the main target of\nToronto fans\' hostility during the American League Championship\nSeries and on Tuesday the big Kansas City outfielder gave them\nmore reason to jeer. Rios collected three hits, including a\nsecond-inning solo home run, as the Royals hammered the Blue\nJays 14-2 to take a 3-1 lead in the best-of-seven series,\nleaving Kansas City one win away from a return to the World\nSeries.</p>", "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-21T08%3A15%3A23.368872%3A570e1485-b572-48d8-a067-daf47cb62755", "subject": [{"qcode": "01021001", "parent": "01021000", "name": "entertainment award"}], "priority": 2, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/tag%3Alocalhost%3A2015%3Abd80492e-f770-4230-9cef-a4ee52b43b69", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-21T08:15:23.368872:570e1485-b572-48d8-a067-daf47cb62755"}}, "urgency": 3, "language": "en", "versioncreated": "2015-10-21T08:15:23+0000", "byline": "me", "located": "Taloqan"}]}',
            array()
        );
        $this->getContentType()->shouldBe(Response::CONTENT_TYPE_JSON);
    }

    function it_should_support_xml_content_type_via_header()
    {
        $this->beConstructedWith(
            '<?xml version="1.0" encoding=UTF-8 standalone="no" ?><error Borked xml <error>',
            array('Content-Type' => Response::CONTENT_TYPE_XML)
        );
        $this->getContentType()->shouldBe(Response::CONTENT_TYPE_XML);
    }

    function it_should_support_xml_content_type_via_content_snooping()
    {
        $this->beConstructedWith(
            '<?xml version="1.0" encoding="UTF-8" standalone="no" ?><validxml>This is valid xml.</validxml>',
            array()
        );
        $this->getContentType()->shouldBe(Response::CONTENT_TYPE_XML);
    }

    function it_should_throw_an_exception_on_content_type_determination_errors()
    {
        $this->beConstructedWith(
            '{ "some key" : "borked json }',
            array()
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ResponseException')->duringInstantiation();

        $this->beConstructedWith(
            '<?xml version="1.0" encoding=UTF-8 standalone="no" ?><error Borked xml <error>',
            array()
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ResponseException')->duringInstantiation();

        $this->beConstructedWith(
            '',
            array()
        );
        $this->shouldThrow('\Superdesk\ContentApiSdk\Exception\ResponseException')->duringInstantiation();
    }

    function it_converts_a_collection_response_properly()
    {
        $this->getType()->shouldBe('packages');
        $this->getHref()->shouldBe('packages?start_date=2015-01-01&max_results=2&page=3');
        $this->getCurrentPage()->shouldBe(3);
        $this->getNextPage()->shouldBe(4);
        $this->getPreviousPage()->shouldBe(2);
        $this->getLastPage()->shouldBe(23);
        $this->getTotalResults()->shouldBe(45);

        $this->getResources()->shouldBeArray();
        $this->getResources()->shouldHaveCount(2);

        $this->isFirstPage()->shouldBe(false);
        $this->isLastPage()->shouldBe(false);
    }

    function it_converts_a_single_response_properly()
    {
        $jsonBody = '{"headline": "foo bar", "version": "1", "type": "composite", "versioncreated": "2015-02-10T06:49:47+0000", "_links": {"parent": {"title": "home", "href": "/"}, "collection": {"title": "packages", "href": "packages"}, "self": {"title": "Package", "href": "packages/tag:example.com,0001:newsml_BRE9A606"}}, "pubstatus": "usable", "language": "en", "uri": "http://api.master.dev.superdesk.org/packages/tag%3Aexample.com%2C0001%3Anewsml_BRE9A606"}';
        $jsonObj = json_decode($jsonBody);
        $this->beConstructedWith(
            $jsonBody,
            array(
                'Connection' => 'keep-alive',
                'Content-Encoding' => 'gzip',
                'Content-Type' => 'application/json',
                'Date' => 'Wed, 11 Nov 2015 13:56:14 GMT',
                'Last-Modified' => 'Mon, 02 Nov 2015 21:15:26 GMT',
                'Server' => 'nginx',
                'Transfer-Encoding' => 'chunked',
                'Vary' => 'Accept-Encoding, Accept-Encoding',
                'X-Total-Count' => '45'
            )
        );

        $this->getType()->shouldBe('Package');
        $this->getHref()->shouldBe('packages/tag:example.com,0001:newsml_BRE9A606');
        $this->getCurrentPage()->shouldBe(null);
        $this->getNextPage()->shouldBe(null);
        $this->getPreviousPage()->shouldBe(null);
        $this->getLastPage()->shouldBe(null);
        $this->getTotalResults()->shouldBe(null);

        $resource = $this->getResources();
        $resource->shouldBeAnInstanceOf('\stdClass');

        foreach ($jsonObj as $property => $value) {
            if ($property == '_links') continue;
            $resource->shouldHaveProperty($property);
        }

        // TODO: Should i care about this...
        // $this->isFirstPage()->shouldBe(false);
        // $this->isLastPage()->shouldBe(false);
    }

    function its_method_is_first_page_should_work()
    {
        $this->beConstructedWith(
            '{"_meta": {"max_results": 2, "total": 45, "page": 1}, "_links": {"self": {"title": "packages", "href": "packages?start_date=2015-01-01&max_results=2&page=1"}, "parent": {"title": "home", "href": "/"}, "next": {"title": "next page", "href": "packages?max_results=2&page=2"}, "last": {"title": "last page", "href": "packages?max_results=2&page=23"}}, "_items": [{"headline": "Reuters Sports News Summary", "pubstatus": "usable", "type": "composite", "body_html": "<p>Following is a summary of current sports news briefs.</p>\n<p>Serena to start 2016 at Hopman Cup in Perth</p>\n<p>World number one Serena Williams will again launch her\nseason at the Hopman Cup in Australia, tournament organizers\nsaid on Wednesday. Williams reached the final of the mixed team\nevent last year with American compatriot John Isner to kick off\na superb season where she won the Australian and French Opens\nand Wimbledon to take her grand slam singles haul to 21.</p>\n<p>In a series hole, Cubs vow to keep swinging</p>\n<p>History has not often been kind to the Cubs, Chicago\'s\npopular but seldom successful north side baseball franchise, but\nthe young team remains hopeful they can continue their\npost-season run. The Cubs survived a one-game playoff to earn a\nspot in the National League\'s divisional series, where they\ndispatched the arch rival St. Louis Cardinals.</p>\n<p>NBA player accuses Milwaukee-area jeweler of racial\nprofiling</p>\n<p>Milwaukee Bucks forward John Henson has accused a\nMilwaukee-area jeweler of racial profiling after its employees\nlocked him out and called police when he went to the store to\nbuy a watch on Monday afternoon. The president of\nSchwanke-Kasten Jewelers issue a statement Monday night\napologizing for the incident at the store in Whitefish Bay, a\nnorthern suburb of Milwaukee.</p>\n<p>Royals rout Jays, one win from World Series berth</p>\n<p>The Kansas City Royals pounced early then powered their way\nto a 14-2 demolition of the Toronto Blue Jays on Tuesday to move\none win away from reaching the World Series for a second\nconsecutive year. The Royals, with the help of a two-run homer\nfrom Ben Zobrist, scored four runs in the first inning then\nsealed the victory with a four-run seventh to seize a commanding\n3-1 lead in the best-of-seven American League Championship\nSeries.</p>\n<p>Mets beat Cubs, one win away from World Series</p>\n<p>The New York Mets snapped a 2-2 tie on a wild-pitch\nstrikeout  on the way to beating the Chicago Cubs 5-2 on\nTuesday, putting them one win away from the World Series. The\nvictory, keyed by the strong pitching of Jacob deGrom and\nanother home run by Daniel Murphy, gave New York a 3-0 lead in\nthe best-of-seven National League Championship Series.</p>\n<p>Yankees\' Tanaka has elbow surgery, will return in 2016</p>\n<p>Japanese right-hander Masahiro Tanaka had surgery on Tuesday\nto remove a bone spur in his pitching elbow and is expected to\nbe ready for the 2016 Major League Baseball season, the New York\nYankees said. The bone spur was pre-existing and dated back to\nTanaka\'s stellar pitching career in his native Japan, according\nto the Yankees.</p>\n<p>Blackhawks\' Keith out 4-6 weeks after knee surgery</p>\n<p>Three-times Stanley Cup-winning Chicago Blackhawks\ndefenseman Duncan Keith is expected to be sidelined between four\nand six weeks after having knee surgery on Tuesday, the National\nHockey League team said. Keith, a three-times All-Star who won\nOlympic gold with Canada at both the 2010 and 2014 Winter Games,\nhad a procedure to repair a meniscal tear in his right knee.</p>\n<p>Lamar Odom making \'incredible strides,\' Khloe Kardashian\nsays</p>\n<p>Reality TV star Khloe Kardashian said on Tuesday her\nestranged husband Lamar Odom has made \"incredible strides\" as he\nrecovers from a collapse in a Las Vegas brothel last week, while\nthe former NBA player\'s aunt called his progress \"miraculous.\"\nBreaking her silence, Kardashian also thanked fans and staff at\nthe Sunrise Hospital in Las Vegas for their support during what\nshe called \"an incredibly difficult\" week.</p>\n<p>NY federal prosecutor probes daily fantasy sports business:\nWSJ</p>\n<p>Manhattan Attorney Preet Bharara is investigating whether\nthe business model behind daily fantasy sports companies such as\nDraftKings Inc and FanDuel Inc violates federal law, the Wall\nStreet Journal reported. The investigation is at an early stage\nand senior Justice Department lawyers in Washington are\nundecided on whether daily fantasy sports betting violates\nfederal gambling statutes, the newspaper reported, citing people\nfamiliar with the matter.</p>\n<p>Royal Rios gives Blue Jays fans more reason to boo</p>\n<p>Former Blue Jay Alex Rios has been the main target of\nToronto fans\' hostility during the American League Championship\nSeries and on Tuesday the big Kansas City outfielder gave them\nmore reason to jeer. Rios collected three hits, including a\nsecond-inning solo home run, as the Royals hammered the Blue\nJays 14-2 to take a 3-1 lead in the best-of-seven series,\nleaving Kansas City one win away from a return to the World\nSeries.</p>", "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-21T08%3A15%3A23.368872%3A570e1485-b572-48d8-a067-daf47cb62755", "subject": [{"qcode": "01021001", "parent": "01021000", "name": "entertainment award"}], "priority": 2, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/tag%3Alocalhost%3A2015%3Abd80492e-f770-4230-9cef-a4ee52b43b69", "type": "text"}]}, "_type": "items", "version": 2, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-21T08:15:23.368872:570e1485-b572-48d8-a067-daf47cb62755"}}, "urgency": 3, "language": "en", "versioncreated": "2015-10-21T08:15:23+0000", "byline": "me", "located": "Taloqan"}, {"_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-10-23T00:47:27.782815:2cb29e8e-cf5c-4304-8fe9-cecc071cbfc9"}}, "body_html": "<p>A dirty, dirty, dirty man has been torn in half in a car crash in Sydney\'s CBD. I srrely can. Wait a bit for mou spellingh mistook.</p><p><br></p><p><br></p><br><p>Father Vince Ryannisha&nbsp;the third discovered the man\'s body.</p><p><br></p><br><p>\"E\'s not in a good way,\" the passer-by said.</p><p>\"I reckon he will be put in a coffin in bits.\"</p><p><br></p>", "pubstatus": "usable", "type": "composite", "subject": [{"qcode": "03000000", "parent": null, "name": "disaster and accident"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-10-23T00%3A47%3A27.782815%3A2cb29e8e-cf5c-4304-8fe9-cecc071cbfc9", "headline": "Dirty priest brown bread following NSW car\u00a0crash", "priority": 3, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-10-23T00%3A42%3A18.286510%3Af9a80c4c-76a1-4d5b-b309-ee118dc249d0", "type": "text"}, {"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-10-23T00%3A47%3A27.730247%3A04af9b68-873c-4637-ae4f-12462e3742de", "type": "text"}, {"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-10-23T00%3A49%3A03.408391%3A02fd0417-c58d-4288-9197-ca7073972c9f", "type": "text"}]}, "versioncreated": "2015-10-23T00:49:03+0000", "_type": "items", "version": 5, "place": [{"qcode": "NSW", "group": "Australia", "country": "Australia", "world_region": "Oceania", "name": "NSW", "state": "New South Wales"}], "urgency": 3, "language": "en", "byline": "", "located": "Fayzabad"}]}',
            array()
        );
        $this->isFirstPage()->shouldBe(true);
    }

    function its_method_is_last_page_should_work()
    {
        $this->beConstructedWith(
            '{"_meta": {"max_results": 2, "total": 45, "page": 23}, "_links": {"self": {"title": "packages", "href": "packages?start_date=2015-01-01&max_results=2&page=23"}, "parent": {"title": "home", "href": "/"}, "prev": {"title": "previous page", "href": "packages?max_results=2&page=22"}}, "_items": [{"pubstatus": "usable", "subject": [{"qcode": "01001000", "parent": "01000000", "name": "archaeology"}], "uri": "http://api.master.dev.superdesk.org/packages/urn%3Anewsml%3Alocalhost%3A2015-11-02T20%3A45%3A44.547837%3A1a1b9503-f958-4948-81a5-bd1db05e3047", "headline": "head", "priority": 1, "associations": {"main": [{"uri": "http://api.master.dev.superdesk.org/items/urn%3Anewsml%3Alocalhost%3A2015-11-02T20%3A44%3A27.205913%3A72694f6c-a2a2-4e39-af3a-f4a57ab2b3c1", "type": "video"}]}, "_type": "items", "version": 4, "_links": {"self": {"title": "Package", "href": "packages/urn:newsml:localhost:2015-11-02T20:45:44.547837:1a1b9503-f958-4948-81a5-bd1db05e3047"}}, "urgency": 3, "description_text": "desc", "slugline": "slug", "language": "en", "versioncreated": "2015-11-02T20:47:39+0000", "byline": "N", "located": "Holdfast Bay", "type": "composite"}]}',
            array()
        );
        $this->isLastPage()->shouldBe(true);
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function ($object, $property) {
                return property_exists($object, $property);
            }
        ];
    }
}
