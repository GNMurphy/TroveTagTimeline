
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
                showMinorTags()
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
        define(QUOTES, "\"");
        define(TAG_QUERY, 'q');
        define(TAG_DISPLAY, 'd');
        define(TAG_OTHER, 'o');
        define(TROVE_N, 100);
        define(TROVE_SORT, "dateasc");
        define(TROVE_ZONE, "all");
        define(TROVE_INCLUDE, "tags,comments");
//      define(TROVE_URL, "http://api.trove.nla.gov.au");
        define(TROVE_URL, "https://api.trove.nla.gov.au/v2");


    function displayBlankForm()
    {
        echo "
            <div class=\"year\">
                <div class=\"form\">
        ";
        formHeader();
        echo "
                    </form>
                </div>
                </div class=\"info\">

                <\div>
            </div>
        ";
    }

    function formHeader()
    {
        echo "
            <form method=\"get\">
        ";
    }

    function initialiseTags()
    {
        if (DEBUG) { print time() . " start initialiseTags<br/>"; }
        global $tags, $queryStrings, $displayStrings;

        $searchTags=$_GET["search"];

        if (DEBUG)
        {
            print("Search tags<br/>");
            print_r($searchTags);
            print("<br/>" . count($searchTags) . "<br/>");
        }

//        array_push($queryStrings, "");
        //        array_push($queryStrings, "NSW 2583");
        array_push($queryStrings, "Biala NSW 2581");
        array_push($queryStrings, "Blakney Creek NSW 2581");
        array_push($queryStrings, "Breadalbane NSW 2581");
        array_push($queryStrings, "Collector NSW 2581");
        array_push($queryStrings, "Dalton NSW 2581");
        array_push($queryStrings, "Gunning NSW 2581");
        array_push($queryStrings, "Lade Vale NSW 2581");
        array_push($queryStrings, "Lake George NSW 2581");
        array_push($queryStrings, "Lerida NSW 2581");
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

          if (DEBUG) {print count($queryStrings) . "<br/>";}

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

    function xinitaliseArticles()
    {
        global $articles, $queryStrings, $displayStrings, $tagIndex;

        array_push($articles, array());
        array_push($articles[0], "Summary");

        // create nodes in articles array for each search and display tag, and record the index in the tags array

        for ($a=0; $a < count($queryStrings); $a++)
        //foreach ($queryStrings as $q => $q_value)
        {
            array_push($articles, array());
            $tagIndex[strtolower($queryStrings[$a])]=array(count($articles)-1,"search");
            $articles[$tagIndex[strtolower($queryStrings[$a])][0]][0]=$queryStrings[$a];
        }

        for ($a=0; $a < count($displayStrings); $a++)
        {
            array_push($articles, array());
            $tagIndex[strtolower($displayStrings[$a])]=array(count($articles)-1,"display");
            $articles[$tagIndex[strtolower($displayStrings[$a])][0]][0]=$displayStrings[$a];
        }

//        print_r($tagIndex);
    }

    function initialTroveQuery()
    {
        if (DEBUG) { print time() . " start initialTroveQuery<br/>"; }

        global $queryStrings, $baseQuery;

        $queryString = PUBLICTAG . QUOTES . $queryStrings[0] . QUOTES ;
		for ($q=1; $q<count($queryStrings); $q++)
		{
		    	$queryString.=" OR " . PUBLICTAG . QUOTES . $queryStrings[$q] . QUOTES;
		}

	    $troveQ=urlencode($queryString);

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


        $x=$troveXML->documentElement;
        if (isset($x)) {
		    if ($x->hasChildNodes())
		    {
                if(DEBUG) {print(time() . " processing articles");}
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

        if (DEBUG) { print time() . " end getArticles<br/>"; }
    }

    function processArticles()
	{
        if (DEBUG) { print time() . " start processArticles<br/>"; }

		global $articles, $tags;

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
        if (DEBUG) { print time() . " end processArticles<br/>"; }
	}

    function xprocessArticles()
	{
		global $articles, $tags, $tagIndex, $queryStrings, $displayStrings;

        //  $articles: an array of article objects retrieved from Trove
        //  $tags    : an associative array indexed by tag (lower case).  Each node contains the tag string and number of articles conatining the tag.


		for ($a=0; $a<count($articles[0]); $a++)
		{
			$article=$articles[0][$a];
			for ($c=0; $c<$article->childNodes->length; $c++)
			{
				$node=$article->childNodes->item($c);
				if ($node->nodeName == "tag")
                {
                    $tag=$node->nodeValue;

                    // if the tag is already in $tags, increment the counter.  if not, add it.
                    if (array_key_exists(strtolower($tag),$tags))
                    {
                        $tags[strtolower($tag)][1]++;
                    }
                    else
                    {
                        $tags[strtolower($tag)]=array($tag, 1, "other");
                        if (array_key_exists(strtolower($tag),$tagIndex))
                        {
                            $tags[strtolower($tag)][2]=$tagIndex[strtolower($tag)][1];
                        }
                    }

                    // if this tag is one of the search or display tags, add the article to the node for this tag
                    if (array_key_exists(strtolower($tag), $tagIndex))
                    {
                        array_push($articles[$tagIndex[strtolower($tag)][0]],$article);
                    }
                }
			}
		}
	}

    function blankRow($rowArray)
    {
		echo "<tr class=\"filler\" height=\"5\">
				<th class=\"headcol\">&#160;</th>
		";
		outputRow($rowArray, "filler");
		echo "</tr>";
    }

    function newFormRow($rowArray, $entryType)
    {

            echo "<tr class=\"groupHeader\"><th class=\"headcol\">Display Tags</th><td colspan=\"" . $columns . "\">&nbsp;</td></tr>";
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

                if ($rowArray[$c][ARTICLE_COUNT] > 0)
                {
    				echo "
                        <a
                            href=\"" . $rowArray[$c][URL] . "\"
                            target=\"_blank\"
                        >
                            <span class=\"" . $class . "\">
                                .
                            </span>
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
        <th class=\"yearHead\">"
        ;
        if ($start - $end == 0)
        {
            echo "</th>";
        }
        else
        {
            echo $start . "-" . $end;
            echo "</th>";

            for ($y=$start; $y<=$end; $y++)
            {
                echo "<td colspan=\"12\" class=\"year\">$y</td>";
            }
            echo "
            </tr>
            ";
        }
    }

    function tagCloud()
    {
        //  presents all tags found in the result set.

        global $tags, $tagMaxMin, $articles;

        $headerToolTip = "Word cloud showing all tags found in the search results. \nTags that are associated with only 1 item are not shown initially; click the chevron to the left to show/hide theses tags";
        $showCloudToolTip = 'Show Tag Cloud';
        $hideCloudToolTip = 'Hide Tag Cloud';
        $showMinorTagsToolTip = 'Show tags that occur in only 1 item';
        $hideMinorTagsToolTip = 'Hide tags that occur in only 1 item';

        //  header
        echo "
            <div class=\"tagCloudHeader\">
                <span title=\"" . $headerToolTip . "\">Tags</span>
                <div class=\"tagCloudHeader_left\">
                    <span
                        class=\"tagCloudButton_left\"
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
                        $count = count($articles[$t_value[1]])-1;
                        if($count > 1)
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
                                title=\"" . $count . " items.\nClick to show on timeline.\"
                                onclick=\"showDisplayTag('$displayId')\"
                            >
                        ";
                        echo $s;
                        echo "
                            </span>
                        ";
                    }
                    echo "
                </span>
            </div>
        ";
    }

    function footer()
    {
        global $totalTime, $troveTime, $troveSearches, $tags;

        echo "
            <footer>
                <div class=\"footer\">
                    <span
                        class=\"footer\"
                        title=\"" . $troveSearches . " Trove searches (" . $troveTime . " seconds)\"
                        >" .
                            count($tags) . " tags
                    </span>
                    <div class=\"footer-left\">
                        Search Completed: " . date("g:ia") . "<br/>" . date("l j F Y") . "
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
            displayBlankForm();
        }
        else
        {
            getArticles(initialTroveQuery());
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
        }

        if (DEBUG) {print("get tag max/min - " . count($articles) . " entries in articles<br/>"); }

        foreach($tags as $t => $t_value)
        {
            if (DEBUG) {print($t_value[1] . " - " . $t_value[2] . "<br/>"); }
            if ($t_value[2]!=TAG_QUERY)
            {
                $count = count($articles[$t_value[1]]) - 1;
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

        echo "
            <div class=\"Timeline\">
        ";
            formHeader();
        echo "
            <table class=\"timelineTable\">
        ";

//        blankRow($blankRowArray);
        yearRow($startYear, $endYear);
//        blankRow($blankRowArray);

        // if only 1 query string, we don't need a summary line
        $startRow=0;
//        if (count($queryStrings) == 1)
//        {
//            $startRow=1;
//        }

        if (DEBUG) {print "count(\$articles) " . count($articles) . "<br/>";}

        for ($d=$startRow; $d<count($articles); $d++)
	    {
		    $rowArray=$blankRowArray;
		    $rowArray[0][LABEL]=$articles[$d][0];
            $rowArray[0][ARTICLE_COUNT]=count($articles[$d])-1;
            $tagType=$tags[strtolower($articles[$d][0])][2];

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
				    }
			    }
			    if ($fullDate <> "")
			    {
				    $year=substr($fullDate,0,4);
				    $month=substr($fullDate,5,2);
				    $col=(($year - $startYear) * 12) + $month;
                    $rowArray[$col][URL]=$url;
                    $rowArray[$col][ARTICLE_COUNT]++;
				    if ($rowArray[$col][LABEL] <> "")
				    {
					    $rowArray[$col][LABEL].="\n";
                        $rowArray[$col][URL]="";
				    }
                    // clean up any characters that will be a problem as an attribute value
                    $heading=str_replace('"', '``', $heading);
                    $heading=str_replace("'", "`", $heading);

                    $rowArray[$col][LABEL].=$fullDate . ", " . $heading;
			    }
		    }

            if ($d==1)
            {
                echo "
                    <tr class=\"groupHeader\">
                        <th class=\"headcol\">
                            <div class=\"groupHeader\">
                                Search Tags
                            </div>
                            <div class=\"headerInfo\">
                                <a
                                    class=\"grouopHeader\"
                                    title=\"These are the terms that are used to search Trove. \nTerms are cumulative (logical OR); items that match any of the Search Tags will be returned\">
                                    i
                                </a>
                            </div>
                        </th>
                        <td colspan=\"" . $columns . "\">&nbsp;</td>
                    </tr>
                ";
            }

            if ($d == count($queryStrings)+1)
            {
                echo "
                    <tr class=\"groupHeader\">
                        <th class=\"headcol\">
                            <div class=\"groupHeader\">
                                Display Tags
                            </div>
                            <div class=\"headerInfo\">
                                <a
                                    class=\"groupHeader\"
                                    title=\"These are tags found in the items returned from the Trove search.  \nAdd tags to this list from the tag cloud \"
                                    >
                                    i
                                </a>
                            </div>
                        </th>
                        <td colspan=\"" . $columns . "\">&nbsp;</td>
                    </tr>
                ";
            }

            if ($rowArray[0][ARTICLE_COUNT] > 0)
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
                    echo  "<input type=\"text\" name=\"search[]\" value=\"" . $rowArray[0][LABEL] . "\"/>";
                }
                else
                {
                    $style="display-event";
                    // if not query tag, then all other rows are display tags, except the summary row ($d==0)
                    if ($d > 0)
                    {
                        $style="summary-event";
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

                $q=$baseQuery;
                if( $tagType == TAG_DISPLAY || $tagType == TAG_OTHER)
                {
                    $q = "(" . $q . ") AND " . PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                }
                if( $tagType == TAG_QUERY)
                {
                    $q = PUBLICTAG . "\"" . $rowArray[0][LABEL] . "\"";
                }

                echo "
                    <div class=\"headerInfo\">
                        <a
                            href=\"https://trove.nla.gov.au/result?q=" . urlencode($q) . "\"
                            target=\"blank\"
                                title=\"" . $rowArray[0][ARTICLE_COUNT] . " items found.  Click to retrieve Trove items.\"
                        >" .
                            $rowArray[0][ARTICLE_COUNT] . "
                        </a>
                    </div>
                ";

                echo "</th>";
		        outputRow($rowArray, $style);
		        echo "
		            </tr>
	            ";
    	    }
        }


        echo "
                </table>
            </form>
        </div>
        ";

        $totalTime=time() - $startTime;

        tagCloud();

        footer();

    ?>

</body>

</html>

