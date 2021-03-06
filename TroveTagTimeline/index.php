
<!DOCTYPE html>
<html>
<head>
    <link rel="Stylesheet" href="stylesheet.css" />
    <script type="text/javascript">
        function showTagCloud() {
            console.log(" - enter showTagCloud()");

            document.getElementById("tagCloud").style.display = "block";
            document.getElementById("showTagCloud_button").style.display = "none";
            document.getElementById("hideTagCloud_button").style.display = "inline";

            //use the Minor Tags functions to expose the appropriate minor tags button
            if (document.getElementsByClassName("tag1")[0].style.display = "none") {
                hideMinorTags();
            }
            else {
                showMinorTags
            }

            console.log(" - exit showTagCloud()");
        }
        function hideTagCloud() {
            document.getElementById("tagCloud").style.display = "none";
            document.getElementById("showTagCloud_button").style.display = "inline";
            document.getElementById("hideTagCloud_button").style.display = "none";
            document.getElementById("showMinorTags_button").style.display = "none";
            document.getElementById("hideMinorTags_button").style.display = "none";
        }
        function showMinorTags() {
            var tags = document.getElementsByClassName("tag1");
            for (var i = 0; i < tags.length; i++) {
                tags[i].style.display = "inline";
            }
            document.getElementById("showMinorTags_button").style.display = "none";
            document.getElementById("hideMinorTags_button").style.display = "inline";
        }
        function hideMinorTags() {
            var tags = document.getElementsByClassName("tag1");
            for (var i = 0; i < tags.length; i++) {
                tags[i].style.display = "none";
            }
            document.getElementById("showMinorTags_button").style.display = "inline";
            document.getElementById("hideMinorTags_button").style.display = "none";
        }
        function showDisplayTag($tagID) {
            document.getElementById($tagID).style.display = "table-row";
        }
        function showHideResultsButton($id) {
            console.log(" - enter showHideResultsButton(" + $id + ")");
            document.getElementById($id).style.visibility = "visible";
            console.log(" - exit showHideResultsButton(" + $id + ")");
        }
        function hideHideResultsButton($id) {
            console.log(" - enter hideHideResultsButton(" + $id + ")");
            document.getElementById($id).style.visibility = "hidden";
            console.log(" - exit hideHideResultsButton(" + $id + ")");
        }
        function addQueryInput() {
            var t = document.getElementById('timelineTable');

            //look for blank input fields
            var e = 0;
            for (var x = 0; x < t.getElementsByTagName('input').length; x++) {
                var n = t.getElementsByTagName('input')[x];
                if (n.value == "") {
                    e++;
                }
            }

            //if no blank input fields, add one
            if (e == 0) {
                var x = t.getElementsByTagName('input').length;

                var r = t.insertRow(x + 2);  // add 3 for Year, Summary and Header rows, less 1 for the button
                r.setAttribute("class", "results");

                var h = document.createElement('th');
                h.setAttribute('class', 'headcol');

                r.appendChild(h);

                var i = document.createElement('input');
                i.setAttribute("name", "searchTag[]");
                i.setAttribute("type", "text")
                i.setAttribute("onchange", "addQueryInput()")
                i.setAttribute("autocomplete", "off")

                h.appendChild(i);
                i.focus();

                // add a hidden element to store the original value so we can detect changes
                var s = document.createElement('span');
                s.setAttribute('display', 'hidden');
                s.value = i.value;

                checkForChanges();
            }
        }
        function checkForChanges()
        {

        }
        function formatDate(date) {
            var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            return days[date.getDay()] + " " + date.getDate() + " " + months[date.getMonth() - 1] + " " + date.getFullYear();                   
        }
        function formatTime(date) {
            var h = date.getHours();
            var m = date.getMinutes();

            var A = "PM";
            if (h < 12) {
                A = "AM";
            }
            else {
                h = h - 12;
            }
            if (h == 0) { h = 12; }

            var mm = m.toString();
            if (m < 10) { mm = "0" + m; }

            return h + ":" + mm + " " + A;
        }
    </script>
</head>

<body>
    <div class="pageHeader">
        <h1>Trove Tag Timeline</h1>
    </div>


    <?php

        // constants
        define(DEBUG, FALSE);
        define(PUBLICTAG, "publictag:");
        define(MAX_ARTICLES,1000);
        define(QUOTES, "\"");
        define(TAG_QUERY, 'q');
        define(TAG_DISPLAY, 'd');
        define(TAG_OTHER, 'o');
        define(TAG_MISC, 'Miscellaneous Tags');
        define(TAG_THRESHOLD, 200);
        define(TROVE_N, 100);
        define(TROVE_SORT, "dateasc");
        define(TROVE_ZONE, "all");
        define(TROVE_INCLUDE, "tags,comments");
//      define(TROVE_URL, "http://api.trove.nla.gov.au");
        define(TROVE_URL, "https://api.trove.nla.gov.au/v2");
        define(QUERY_INPUT, '<input type="text" name="searchTag[]" onchange="addQueryInput()" autocomplete="off" ');


    function displayBlankForm($message)
    {
        //position:absolute; left:0;
        // style=\"margin-top:6px; \"
        if ($message != "")
        {
            echo "<div class=\"alert\">$message</div>";
        }
        else
        {
            echo "<div class=\"year\" style=\"width:100%;\">&nbsp;</div>";
        }
        echo "
            <div class=\"timelineBase\">

            <div class=\"form\">
        ";

        formHeader();
//        yearRow(0,0);

        echo "
                        <tr class=\"results\" style=\"display:none;\">
                            <th class=\"headcol\">&nbsp;</th>
                        </tr>
                        <tr class=\"results\">
                            <th class=\"headcol\">&nbsp;</th>
                        </tr>
        ";
        searchTagHeader();
        echo "
                        <tr class=\"results\">
                            <th class=\"headcol\">
                                " .QUERY_INPUT .  " id=\"input1\" />
                            </th>
                        </tr>
                        <tr class=\"results\">
                            <th class=\"headcol\">
                                " .QUERY_INPUT .  " />
                            </th>
                            <td>&nbsp;</td>
                        </tr>
        ";
        displayTagHeader();
        searchButton();
        echo "
                    </table>
                </form>
            </div>
            <div class=\"info\" style=\"vertical-align:middle;padding:10px 0 10px 20px;\" >
                <div class=\"info\">Enter one or more search terms into the Search Tag fields. (Additional fields will display when the existing ones are used.)</div>
                <div class=\"info\">All results will be shown on the top 'Summary' row.  Exact matches will be displayed against each tag.</div>
                    <table class=\"info\" style=\"border: none;\">
                        <tr>
                            <td class=\"info\">Examples:</td>
                            <td class=\"info\"><strong>Lade Vale NSW 2581</strong></td>
                            <td class=\"info\" style=\"white-space: normal;\">will display a number of exactly matching results</td>
                        </tr>
                        <tr>
                            <td class=\"info\">&nbsp;</td>
                            <td class=\"info\"><strong>NSW 2581</strong></td>
                            <td class=\"info\" style=\"white-space: normal;\">
                                is unlikely to return any exact matches, but will return a number of results with tags that include 'NSW&nbsp;2581'
                                <br/>
                                These results will be displayed in the summary row.
                            </td>
                        </tr>
                    </table>
            </div>
            <script>
                document.getElementById(\"input1\").focus();
            </script>
        </div>
        ";
    }

    function blankInput()
    {
        echo "
                        <tr class=\"results\">
                            <th class=\"headcol\">
                                " .QUERY_INPUT .  "/>
                            </th>
                        </tr>
        ";
    }

    function formHeader()
    {
        echo "
            <form method=\"post\">
                <table class=\"timelineTable\" id=\"timelineTable\">

        ";
    }

    function searchTagHeader()
    {
        global $columns;
        $c=$columns;
        if ($c ==0) {$c=1;}

//                                    class=\"groupHeader\"
        echo "
                    <tr class=\"groupHeader\">
                        <th class=\"headcol\">
                            <div class=\"groupHeader\">
                                Search Tags
                            </div>
                            <div class=\"headerInfo\">
                                <img
                                    class=\"headerInfo\"
                                    src=\"i-circled.png\"
                                    title=\"These are the terms that are used to search Trove. \nTerms are cumulative (logical OR); items that match any of the Search Tags will be returned\"
                                />
                            </div>
                        </th>
                        <td colspan=\"" . $c . "\">&nbsp;</td>
                    </tr>
        ";
    }

    function displayTagHeader()
    {
        global $columns, $miscTagThreshold;
        $c=$columns;
        if ($c ==0) {$c=1;}

        echo "
                    <tr class=\"groupHeader\">
                        <th class=\"headcol\">
                            <div class=\"groupHeader\">
                                Display Tags
                            </div>
                            <div class=\"headerInfo\">
                                <img
                                    class=\"headerInfo\"
                                    src=\"i-circled.png\"
                                    title=\"These are tags found in the items returned from the Trove search.\n" .
                                            "Up to 200 tags are displayed, the remainder (appearing in " . $miscTagThreshold . " or less articles) are grouped as " . TAG_MISC . ".\n" .
                                            "Add tags to this list from the tag cloud \"
                                />
                            </div>
                        </th>
                        <td colspan=\"" . $c . "\">&nbsp;</td>
                    </tr>
                ";
    }

    function searchButton()
    {
        echo "
                    <tr class=\"groupHeader\">
                        <th class=\"headcol\">
                            <div class=\"submit\">
                                <input type=\"submit\" value=\"Search\"/>
                            </div>
                        </th>
                    </tr>
        ";
    }

    function initialiseTags()
    {
        if (DEBUG) { print time() . " start initialiseTags<br/>"; }
        global $tags, $queryStrings, $displayStrings;

        foreach ($_POST["searchTag"] as $s) {
            if(trim($s) != '') {
                array_push($queryStrings, $s);
            }
        }

        if (DEBUG)
        {
            print("Search tags<br/>");
            print_r($queryStrings);
            print("<br/>" . count($queryStrings) . " - " . count($_POST['searchTag']) . "<br/>");
        }

//        array_push($queryStrings, "");
//        array_push($queryStrings, "NSW 2583");
//        array_push($queryStrings, "Biala NSW 2581");
//        array_push($queryStrings, "Blakney Creek NSW 2581");
//        array_push($queryStrings, "Breadalbane NSW 2581");
//        array_push($queryStrings, "Collector NSW 2581");
//        array_push($queryStrings, "Dalton NSW 2581");
//        array_push($queryStrings, "Gunning NSW 2581");
//        array_push($queryStrings, "Lade Vale NSW 2581");
//        array_push($queryStrings, "Lake George NSW 2581");
//        array_push($queryStrings, "Lerida NSW 2581");
//        array_push($queryStrings, "NSW 2581");

//        array_push($queryStrings, "Grabben Gullen NSW 2583");

//        array_push($displayStrings,"Education");
//        array_push($displayStrings,"Benbengenoe School");
//        array_push($displayStrings,"Berebangelo School");
//        array_push($displayStrings,"Bevandale School");
//        array_push($displayStrings,"Blakney Creek School");
//        array_push($displayStrings,"Breadalbane Public School");
//        array_push($displayStrings,"Chain of Ponds Public School");
//        array_push($displayStrings,"Cullerin School");
//        array_push($displayStrings,"Dalton Public School");
//        array_push($displayStrings,"Felled Timber Creek Public School");
//        array_push($displayStrings,"Ferncliffe School");
//        array_push($displayStrings,"Frankfield Public School");
//        array_push($displayStrings,"Grabben Gullen Public School");
//        array_push($displayStrings,"Gunning Public School");
//        array_push($displayStrings,"Jerrawa Public School");
//        array_push($displayStrings,"Lade Vale Public School");
//        array_push($displayStrings,"Lerida School");
//        array_push($displayStrings,"Merrill Creek Public School");
//        array_push($displayStrings,"Nelanglo Public School");
//        array_push($displayStrings,"Waggalalah / Langley Park Public School");

        //	array_push($displayStrings,"Births Deaths & Marriages");
        //	array_push($displayStrings,"Land Office / Land Sales");
        //	array_push($displayStrings,"Poundkeeper");
        //	array_push($displayStrings,"Town Allotments");

//          array_push($displayStrings,"Rural Property");
//          array_push($displayStrings,"Boureong");
//          array_push($displayStrings,"Collingwood");
//          array_push($displayStrings,"Frankfield");
//          array_push($displayStrings,"Keswicke");
//          array_push($displayStrings,"Inglewood");
//          array_push($displayStrings,"Eschol");

        if (DEBUG)
        {
            print count($queryStrings) . "<br/>";
        }

        //  add each query tag to $tags and create an entry in $articles
        for ($i=0; $i<count($queryStrings); $i++)
        {
            $tag=$queryStrings[$i];
            insertTag($tag, TAG_QUERY);
            insertArticle($tag);
        }

        //  add each dispay tag to $tags but don't create the $articles entry yet.  It will be create when(if) and associated article if found
        for ($i=0; $i<count($displayStrings); $i++)
        {
            $tag=$displayStrings[$i];
            insertTag($tag, TAG_DISPLAY);
        }

        if (DEBUG) { print time() . " end initialiseTags<br/>"; }
    }

    function initaliseArticles()
    {
        if (DEBUG) { print time() . " start initialiseArticles<br/>"; }
        global $articles;

        array_push($articles, array());
        array_push($articles[0], "Summary");

        initialiseTags();

        if (DEBUG) { print time() . " end initialiseArticles<br/>"; }
    }

    function insertTag($tag, $tagType)
    {
        if (DEBUG) { print time() . " start insertTag(" . $tag . ", " . $tagType . ")<br/>"; }

        global $tags;

        $tagKey=strtolower($tag);

        if (!array_key_exists($tagKey,$tags))
        {
            $tags[$tagKey]=array($tag, -1, $tagType);
        }

        if (DEBUG) { print time() . " end insertTag<br/>"; }
    }

    function insertArticle($tag)
    {
        //  create a new entry in $articles for this tag and record the index in $tags

        if (DEBUG) { print time() . " start insertArticle(" . $tag . ")<br/>"; }

        global $articles, $tags;

        $tagKey = strtolower($tag);

        if (array_key_exists($tagKey, $tags))
        {
            if (DEBUG) { print("\$tags[\$tagKey][1] - " . $tags[$tagKey][1]) . "<br/>";}

            if ($tags[$tagKey][1] == -1)
            {
                array_push($articles, array());
                $tags[$tagKey][1]=count($articles)-1;

                $articles[$tags[$tagKey][1]][0]=$tag;
            }
        }

        if (DEBUG) { print time() . " end insertArticle<br/>"; }
    }

    function initialTroveQuery()
    {
        if (DEBUG) { print time() . " start initialTroveQuery<br/>"; }

        global $queryStrings, $baseQuery;

        $baseQuery = PUBLICTAG . QUOTES . $queryStrings[0] . QUOTES ;
		for ($q=1; $q<count($queryStrings); $q++)
		{
		    	$baseQuery.=" OR " . PUBLICTAG . QUOTES . $queryStrings[$q] . QUOTES;
		}


	    $troveQ=urlencode($baseQuery);

        if (DEBUG) { print time() . " end initialTroveQuery<br/>"; }

        return("/result?" . "&zone=" . TROVE_ZONE . "&q=" . $troveQ . "&n=" . TROVE_N . "&sortby=" . TROVE_SORT . "&include=" . TROVE_INCLUDE);
    }

    function getArticles($troveQueryString)
    {
        if (DEBUG) { print time() . " start getArticles(" . $troveQueryString . ")<br/>"; }

		global $articles, $startYear, $endYear, $troveTime, $troveSearches;

        //Console.log($troveQueryString);

		$troveKey="jsujmjnh7ah962h4";
		$troveXML = new DOMDocument();
        $url=TROVE_URL . $troveQueryString . "&key=" . $troveKey;

        if (DEBUG) {print(time() . "calling Trove - " . $url . "<br/>");}

        $troveStart = time();
  		$troveXML->load(TROVE_URL . $troveQueryString . "&key=" . $troveKey);
        $troveTime += time()-$troveStart;
        $troveSearches++;
        $totalArticles=0;


        $x=$troveXML->documentElement;
        if (isset($x)) {
		    if ($x->hasChildNodes())
		    {
                if(DEBUG) {print(time() . " processing articles");}

                // calculate the total number of records returned.
                // if it is too big, we can't progress

                foreach ($x->getElementsByTagName("records") as $records)
			    {
				    $t = $records->attributes->getNamedItem("total");
                    if (isset($t))
				    {
					    $totalArticles+=$t->nodeValue;
				    }
			    }

                if ($totalArticles <= MAX_ARTICLES)
                {
                    // process the articles
                    // add each article to the article array, and find the earlist and latest year
                    foreach ($x->getElementsByTagName("article") as $article)
                    {
                        array_push($articles[0],$article);
                        foreach ($article->childNodes as $articleNode)
                        {
                            //$articleNode = $article[$c];
                            //echo $articleNode->nodeName;
                            if ($articleNode->nodeName == "date")
                            {
                                $date=$articleNode->nodeValue;
                                //echo $date;
                                if (!isSet($startYear) || $startYear==0 || substr($date,0,4) < $startYear)
                                {
                                    $startYear=substr($date,0,4);
                                }
                                if (!isSet($endYear) || substr($date,0,4) > $endYear)
                                {
                                    $endYear=substr($date,0,4);
                                }
                                //echo "(" . $startYear . "-" . $endYear . ")";
                            }
                        }
                    }

                    // look for any zone that have more articles to retrieve
                    foreach ($x->getElementsByTagName("records") as $records)
                    {
                        $next = $records->attributes->getNamedItem("next");
                        if (isset($next))
                        {
                            getArticles($next->nodeValue);
                        }
                    }
                }
		    }
        }

        if (DEBUG) { print time() . " end getArticles<br/>"; }
        return $totalArticles;
    }

    function processArticles()
	{
        if (DEBUG) { print time() . " start processArticles<br/>"; }

		global $articles, $tags, $miscTagThreshold, $displayStrings, $tagMaxMin;

        //  $articles:  an array of article objects retrieved from Trove
        //                  first row is all articles
        //                  remaining rows have articles for each tag
        //                structure:
        //                  [0]     tag
        //                  [1]-[n] articles containing the tag
        //              $articles has been initialised with query tags.
        //                  Display tags will be added with the first articles found - to exclude tags that have no articles
        //  $tags:      an associative array indexed by tag (lower case).
        //                structure:
        //                  key:    tag (lower case)
        //                  value:
        //                      [0]     tag
        //                      [1]     index to articles array.
        //                      [2]     tag type - query, display, other
        //              $tags has been initialised with the query and display tags.
        //                  The articles index (value[1]) for display tags is set to -1 and will be be initialised with the first article.

        //  process each article retrieved and add it to the appropriate row(s) in $articles
		for ($a=0; $a<count($articles[0]); $a++)
		{
			$article=$articles[0][$a];
			for ($c=0; $c<$article->childNodes->length; $c++)
			{
				$node=$article->childNodes->item($c);
				if ($node->nodeName == "tag")
                {
                    $tag=$node->nodeValue;
                    $tagKey=strtolower($tag);

                    // if the tag is not in $tags, add it
                    if (!array_key_exists($tagKey,$tags))
                    {
                        insertTag($tag, TAG_OTHER);
                    }

                    // check the $articles index and create the $articles node if required.
                    if ($tags[$tagKey][1] < 0)
                    {
                        insertArticle($tag);
                    }

                    // add the article to the articles entry for this tag
                    array_push($articles[$tags[$tagKey][1]],$article);
                }
			}
		}

        // if there are too many tags, add a row to articles for tags with only 1 article.
        // By doing this, we don't need a seperate row for these tags and and don't need to include the tags in the tag cloud,
        // significantly reducing the size of the html.

        // calculate $miscTagThreshold and $tagMaxMin
        tagStats();

        if ($miscTagThreshold > 0 )
        {
            // add the tag to $tags
            insertTag(TAG_MISC, TAG_DISPLAY);
            // create the $articles node
            insertArticle(TAG_MISC);
            array_push($displayStrings, TAG_MISC);

            // find all tags that appear in less than the minimum number of articles and add the articles to the TAG_MISC row
            for ($a=1; $a<count($articles)-1; $a++)
            {
                if (count($articles[$a]) - 1 <= $miscTagThreshold)
                {
                    for ($a2=1; $a2<count($articles[$a]); $a2++)
                    {
                        // make sure that the article isn't already in the list
                        $articleId = getArticleId($articles[$a][$a2]);
                        $x=0;
                        $articleIndex=count($articles)-1;

                        for ($a3=1; $a3<count($articles[$articleIndex]); $a3++)
                        {
                            // compare article ids & increment $x if they match
                            if (getArticleId($articles[$articleIndex][$a3]) == $articleId)
                            {
                                $x++;
                                // we can stop searching now - we know the article is already listed
                                $a3=count($articles[$articleIndex]);
                            }
                        }

                        // If we didn't find the article, add it
                        if ($x == 0)
                        {
                            array_push($articles[count($articles)-1],$articles[$a][$a2]);
                        }
                    }
                }
            }
        }

        if (DEBUG) { print time() . " end processArticles<br/>"; }
	}

    function tagStats()
    {
        global $tagMaxMin, $tags, $miscTagThreshold, $articles;

        $tagCounts=array();
        $tagCounts[1]=0;

        foreach($tags as $t => $t_value)
        {
            if (DEBUG) {print($t_value[1] . " - " . $t_value[2] . "<br/>"); }

            $count = count($articles[$t_value[1]]) - 1;
            if (!array_key_exists($count,$tagCounts))
            {
                $tagCounts[$count]=0;
            }
            $tagCounts[$count]++;

            if ($t_value[2]!=TAG_QUERY)
            {
                if (DEBUG) {print($count . "<br/>");}

                if ($count > $tagMaxMin[0])
                {
                    $tagMaxMin[0]=$count;
                    $tagMaxMin[2]=$t;
                }
                if ($count < $tagMaxMin[1])
                {
                    $tagMaxMin[1]=$count;
                }
            }
        }

        $miscTags=0;
        while (count($tags) - $miscTags > TAG_THRESHOLD)
        {
            $miscTagThreshold++;
            $miscTags+=$tagCounts[$miscTagThreshold];
        }
    }

    function getArticleId($article)
    {
        foreach($article->attributes as $attr){
            if ($attr->name == "id")
            {
                return $attr->value;
            }
        }
        return null;
    }

    function blankRow($rowArray)
    {
		echo "<tr class=\"filler\" height=\"5\">
				<th class=\"headcol\">&#160;</th>
		";
		outputRow($rowArray, "filler");
		echo "</tr>";
    }

    function outputRow($rowArray, $class)
    {
		for ($c=1; $c < count($rowArray); $c++)
		{
			if ($rowArray[$c][COLSPAN] > 0)
			{
				echo "
                    <td
                        colspan=\"" . $rowArray[$c][COLSPAN] . "\"
                        title=\"" . $rowArray[$c][LABEL] . "\"
                    >
                ";

                if ($rowArray[$c][URL] != "")
                {
    				echo "
                        <a
                            href=\"" . $rowArray[$c][URL] . "\"
                            target=\"_blank\"
                        >
                    ";
                }
                if ($rowArray[$c][ARTICLE_COUNT] > 0)
                {
                    echo "
                            <span class=\"" . $class . "\">
                                .
                            </span>
                    ";
                }
                if ($rowArray[$c][URL] != "")
                {
                    echo "
                        </a>
                    ";
                }
                echo "
                    </td>
                ";
			}
		}
    }

    function yearRow($start, $end)
    {
        echo "
                <tr class=\"year\">
                <th class=\"yearHead\">
        ";
        if ($start - $end == 0)
        {
            echo "
                    &nbsp;
                </th>
            ";
        }
        else
        {
            echo $start . "-" . $end;
            echo "
                </th>
            ";

            for ($y=$start; $y<=$end; $y++)
            {
                echo "
                    <td colspan=\"12\" class=\"year\">$y</td>
                ";
            }
        }
        echo "
                </tr>
        ";
    }

    function tagCloud()
    {
        //  presents all tags found in the result set.

        global $tags, $tagMaxMin, $articles, $miscTagThreshold;

        $headerToolTip = "Word cloud showing tags found in the search results.";
        $headerToolTip1 = "\nTags that are associated with only 1 item are not shown; articles for these tags are displayed on the Miscellaneous Tags line";
        $headerToolTip2 = "\nTags that are associated with " . $miscTagThreshold . " or less items are not shown initially; click the chevron to the left to show/hide theses tags";
        $showCloudToolTip = 'Show Tag Cloud';
        $hideCloudToolTip = 'Hide Tag Cloud';
        $showMinorTagsToolTip = "Show tags that occur in ";
        $hideMinorTagsToolTip = "Hide tags that occur in ";
        if ($miscTagThreshold == 1)
        {
            $showMinorTagsToolTip = $showMinorTagsToolTip . "only 1 item";
            $hideMinorTagsToolTip = $hideMinorTagsToolTip . "only 1 item";
        }
        else
        {
            $showMinorTagsToolTip = $showMinorTagsToolTip . $miscTagThreshold . " or less items";
            $hideMinorTagsToolTip = $hideMinorTagsToolTip . $miscTagThreshold . " or less items";
        }

        //  header
        echo "
            <div class=\"tagCloudHeader\">
                <span title=\"" . $headerToolTip;
        if ($miscTagThreshold > 0)
        {
            if ($miscTagThreshold == 1)
            {
                echo $headerToolTip1;
            }
            else
            {
                echo $headerToolTip2;
            }
        }
        echo "\">Tags</span>
                <div class=\"tagCloudHeader_left\">
                    <span
                        class=\"tagCloudButton_left\"
        ";
        if ($miscTagThreshold == 0)
        {
            echo "
                        >
                        &nbsp;
            ";
        }
        else
        {
            echo "
                        title=\"" . $showMinorTagsToolTip . "\"
                        id=\"showMinorTags_button\"
                        onclick=\"showMinorTags()\"
                        >
                        &#187;
                    </span>
                    <span
                        class=\"tagCloudButton_left\"
                        title=\"" . $hideMinorTagsToolTip . "\"
                        id=\"hideMinorTags_button\"
                        onclick=\"hideMinorTags()\"
                        >
                        &#171;
            ";
        }
        echo "
                    </span>
                    &nbsp;
                </div>
                <div class=\"tagCloudHeader_right\">
                    <span
                        class=\"tagCloudButton_right\"
                        title=\"" . $showCloudToolTip . "\"
                        id=\"showTagCloud_button\"
                        onclick=\"showTagCloud()\"
                        >
                        +
                    </span>
                    <span
                        class=\"tagCloudButton_right\"
                        title=\"" . $hideCloudToolTip . "\"
                        id=\"hideTagCloud_button\"
                        onclick=\"hideTagCloud()\"
                        >
                        &#215;
                    </span>
                </div>
            </div>
        ";

        //  body
        ksort($tags);
        echo "
            <div class=\"tagCloud\" id=\"tagCloud\" >
                <span class=\"tagCloud\"><br/>";
                    foreach($tags as $t => $t_value)
                    {
                        if ($t_value[0] != TAG_MISC)
                        {
                            $count = count($articles[$t_value[1]])-1;
                            if($count > $miscTagThreshold)
                            {
                                $tagGroup=ceil($count/(ceil($tagMaxMin[0]/8))) + 1;
                                if ($tagGroup > 9)
                                {
                                    $tagGroup = 9;
                                }
                            }
                            else
                            {
                                $tagGroup=1;
                            }
                            $s = str_replace(" ", "&nbsp;", $t_value[0]);
                            $classId = "tag" . $tagGroup;
                            $displayId = "display$t_value[1]";

                            echo "
                                <span
                                    class=\"$classId\"
                            ";

                            // if there are too many tags, we consolidate the those with fewer articles into a single timeline,
                            // so no onclick for them, and a different title
                            if ($count > $miscTagThreshold)
                            {
                                echo "
                                    title=\"$count items.\nClick to show on timeline.\"
                                    onclick=\"showDisplayTag('$displayId')\"
                                >
                                ";
                            }
                            else
                            {
                                echo "
                                    title=\"$count item";
                                if ($count>1)
                                {
                                    echo "s.\nThese items are ";
                                }
                                else
                                {
                                    echo ".\nThis item is ";
                                }
                                echo "displayed on the " . TAG_MISC . " timeline.\"
                                >
                                ";
                            }
                            echo $s;
                            echo "</span>";
                        }
                    }
                    echo "
                </span>
            </div>
        ";
    }

    function footer()
    {
        global $totalTime, $troveTime, $troveSearches, $tags, $queryStrings;

        echo "
            <footer>
                <div class=\"footer\">
                    <span
                        class=\"footer\"
        ";
        if (count($queryStrings) == 0)
        {
            echo "
                         >
                        &nbsp;
            ";
        }
        else
        {

            echo"
                        title=\"" . $troveSearches . " Trove searches (" . $troveTime . " seconds)\"
                        >
            ";
            echo        count($tags) . " tags";
        }
        echo "
                    </span>
                    <div class=\"footer-left\">
        ";
        if (count($queryStrings) == 0)
        {
            echo "
                        &nbsp;
            ";
        }
        else
        {
            echo "Search Completed: <span id=\"searchTime\"></span><br/><span id=\"searchDate\"></span>";
            echo "
                        <script>
                            {
                                var d=new Date(\"" . gmdate('j F Y h:i:s A') . " UTC\");
                                document.getElementById('searchTime').innerHTML=formatTime(d);
                                document.getElementById('searchDate').innerHTML=formatDate(d);
                            }
                        </script>
            ";
        }
        echo "
                    </div>
                    <div class=\"footer-right\">
                        <span class=\"footer\">
                            <img src=\"http://help.nla.gov.au/sites/default/files/trove_author/API-light.png\" />
                        </span>
                    </div>
                </div>
            </footer>
        ";
    }


    // Global declarations
    $startTime=time();
    $troveTime=0;
    $troveSearches=0;

	$queryStrings=array();
    $baseQuery="";
	$displayStrings=array();
    $columns=0;
    $startYear=0;
    $endYear=0;
    $articles = array();
    $tags =array();
    //$tagIndex = array();
    $tagMaxMin=array(0,0,"");
    $miscTagThreshold = 0;

    // get query and display tags and initialise $articles array
    initaliseArticles();

    if (DEBUG)
    {
        print("Tags" . "<br/>");
        print_r (array_keys($tags) . "<br/>");
        print("<br/>" . count($tags) . "<br/>");
        print("Articles" . "<br/>");
        print_r ($articles);
        print("<br/>" . count($articles) . "<br/>");
        print("Query Tags" . "<br/>");
        print_r ($queryStrings);
        print("<br/>" . count($queryStrings) . "<br/>");
        print("Display Tags" . "<br/>");
        print_r ($displayStrings);
        print("<br/>" . count($displayStrings) . "<br/>");
    }


    // if there are no query tags, display an input form only
    if (count($queryStrings) == 0)
    {
        displayBlankForm("");
    }
    else
    {
        $total = getArticles(initialTroveQuery());
        if ($total > MAX_ARTICLES)
        {
            // display blank for mwith message suggesting a narrower search string
            $message="Your search returned ". $total . " articles.  Refine the search to return fewer articles";
            displayBlankForm($message);
        }
        else
        {
            processArticles();
            $columns=(($endYear - $startYear) + 1) * 12;

            if (DEBUG)
            {
                print("Tags" . "<br/>");
                foreach ($tags as $t => $t_value)
                {
                    print($t . ": ");
                    print_r ($t_value);
                    print("<br/>");
                }
                print(count($tags) . "<br/>");
            }


            if (DEBUG) {print("get tag max/min - " . count($articles) . " entries in articles<br/>"); }

            // record the max and min of the number of articles for tags, and work out what the threshold is for display tags ($miscTagThreshold)


            if (count($displayStrings) == 0)
            {
                $tags[$tagMaxMin[2]][2]=TAG_DISPLAY;
            }

            if (DEBUG)
            { print_r($tagMaxMin . "<br/>"); }

            $blankRowArray=array();
            define(LABEL, 0);
            define(COLSPAN, 1);
            define(URL, 2);
            define(ARTICLE_COUNT, 3);

            for ($x=1;$x<=$columns;$x++)
            {
                $blankRowArray[$x][LABEL]="";
                $blankRowArray[$x][COLSPAN]=1;
                $blankRowArray[$x][URL]="";
                $blankRowArray[$x][ARTICLE_COUNT]=0;
            }

            //    echo $startYear . "-" . $endYear . " (" . $columns . ")<br/>";
            //            <div class=\"timelineBase\">
            //style=\"position: absolute; left: 0; z-index: -1;\"
            echo "
            <div class=\"timelineBase\">
                <div class=\"form\" style=\"z-index: -1;\" >&nbsp;</div>
                    <div class=\"timeline\">
        ";
            formHeader();
            yearRow($startYear, $endYear);


            if (DEBUG) {print "count(\$articles) " . count($articles) . "<br/>";}

            for ($d=0; $d<count($articles); $d++)
            {
                $tagType=$tags[strtolower($articles[$d][0])][2];
                $count = count($articles[$d])-1;

                // if there are  too many tags, don't show the ones with only fewer articles

                if($tagType == TAG_QUERY || $tagType == TAG_DISPLAY || $count > $miscTagThreshold)
                {
                    $rowArray=$blankRowArray;
                    $rowArray[0][LABEL]=$articles[$d][0];
                    $rowArray[0][ARTICLE_COUNT]=count($articles[$d])-1;

                    $q=$baseQuery;
                    if( $tagType == TAG_DISPLAY || $tagType == TAG_OTHER)
                    {
                        $q = "(" . $q . ") AND " . PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                    }
                    if( $tagType == TAG_QUERY)
                    {
                        $q = PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                    }

                    $rowArray[0][URL]="https://trove.nla.gov.au/result?q=" . urlencode($q);

                    if (DEBUG) {print "count(\$articles\[" . $d . "] " . count($articles[$d]) . "<br/>";}


                    for ($a=1; $a < count($articles[$d]); $a++)
                    {
                        $article=$articles[$d][$a];
                        $fullDate="";
                        $heading="";
                        $url="";

                        for ($c=0; $c<$article->childNodes->length; $c++)
                        {
                            $node=$article->childNodes->item($c);
                            if ($node->nodeName == "date")
                            {
                                $fullDate=$node->nodeValue;
                            }
                            if ($node->nodeName == "heading")
                            {
                                $heading=$node->nodeValue;
                            }
                            if ($node->nodeName == "troveUrl")
                            {
                                $url=$node->nodeValue;
                                if (strpos($url,"?")) {
                                    $url = substr($url, 0, strpos($url,"?"));
                                }
                            }
                        }
                        if ($fullDate <> "")
                        {
                            $year=substr($fullDate,0,4);
                            $month=substr($fullDate,5,2);
                            $col=(($year - $startYear) * 12) + $month;
                            $rowArray[$col][URL]=$url;
                            $rowArray[$col][ARTICLE_COUNT]++;
                            if ($rowArray[$col][ARTICLE_COUNT] > 1)
                            {
                                $rowArray[$col][LABEL].="\n";
                                $rowArray[$col][URL]=$rowArray[0][URL] . "&l-year=" . $year;
                            }
                            // clean up any characters that will be a problem as an attribute value
                            $heading=str_replace('"', '``', $heading);
                            $heading=str_replace("'", "`", $heading);

                            $rowArray[$col][LABEL].=$fullDate . ", " . $heading;
                        }
                    }

                    if ($d==1)
                    {
                        searchTagHeader();
                    }

                    // when we have displayed all the query tags, add a blank input then display all the other tags
                    if ($d == count($queryStrings)+1)
                    {
                        blankInput();
                        displayTagHeader();
                    }

                    if ($tagType == TAG_QUERY || $rowArray[0][ARTICLE_COUNT] > 0)
                    {
                        echo "
		            <tr class=\"results\"
                        id=\"display$d\"
                    ";
                        if ($tagType == TAG_OTHER)
                        {
                            echo "style=\"display: none;\"";
                        }

                        echo "
                    >
		                <th class=\"headcol\"
                    ";
                        if ($tagType == TAG_DISPLAY || $tagType == TAG_OTHER)
                        {
                            echo "
                            onmouseover=\"document.getElementById('hideResults$d').style.visibility='visible'\"
                            onmouseout=\"document.getElementById('hideResults$d').style.visibility='hidden'\"
                    ";
                        }

                        echo "
                        >
                    ";

                        $style="query-event";
                        if ($tagType == TAG_QUERY)
                        {
                            echo  QUERY_INPUT . " value=\"" . $rowArray[0][LABEL] . "\"/>";
                        }
                        else
                        {
                            $style="summary-event";
                            // if not query tag, then all other rows are display tags, except the summary row ($d==0)
                            if ($d > 0)
                            {
                                $style="display-event";
                                echo "
                            <span
                                class=\"hideDisplayResults\"
                                id=\"hideResults$d\"
                                title=\"Remove\"
                                onclick=\"document.getElementById('display$d').style.display='none';\"
                            >
                            x
                            </span>
                        ";
                            }
                            echo "
                        <span class=\"displayHeader\">" . $rowArray[0][LABEL] . "</span>
                    ";
                        }

                        // Show number of articles to the right of the header, with a link to Trove to retrieve the articles.
                        // The summary line ($d==0) uses the same query string as the original search ($baseQuery), query tag lines use the query tag only,base
                        // and display lines use $baseQuery AND queryTag.

                        //                $q=$baseQuery;
                        //                if( $tagType == TAG_DISPLAY || $tagType == TAG_OTHER)
                        //                {
                        //                    $q = "(" . $q . ") AND " . PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                        //                }
                        //                if( $tagType == TAG_QUERY)
                        //                {
                        //                    $q = PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                        ///                }
                        //                            href=\"https://trove.nla.gov.au/result?q=" . urlencode($q) . "\"

                        echo "
                        <div class=\"headerInfo\">";
                        if ($rowArray[0][LABEL] == TAG_MISC)
                        {
                            echo "<span title=\"" . $rowArray[0][ARTICLE_COUNT] . " items with tags appearing in " . $miscTagThreshold . " or less articles.\">" .  $rowArray[0][ARTICLE_COUNT] . "</span>";
                        }
                        else
                        {
                            echo "
                            <a
                                href=\"" . $rowArray[0][URL] . "\"
                                target=\"blank\"
                                    title=\"";
                            if ($rowArray[0][ARTICLE_COUNT] > 0) {
                                echo $rowArray[0][ARTICLE_COUNT] . " items.\nClick to show on timeline.";
                            }
                            else
                            {
                                echo "No exact matches found. Items with tags containing this search term are shown in the summary line.\nSelect tags from the Tag Cloud to view related items in Display Tags";
                            }
                            echo "\"
                                >" .
                            $rowArray[0][ARTICLE_COUNT] . "
                                </a>
                        ";
                        }
                        echo "
                            </div>
                        </th>
                    ";
                        outputRow($rowArray, $style);
                        echo "
		            </tr>
	                ";
                    }
                }
            }

            searchButton();

            echo "
                    </table>
                </form>
            </div>
        </div>
        ";

            $totalTime=time() - $startTime;

            tagCloud();
        }
    }

//    echo "
//        </div>
//    ";

    footer();
    ?>

</body>

</html>

