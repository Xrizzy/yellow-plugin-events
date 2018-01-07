<div class="content">
<?php $yellow->snippet("sidebar") ?>
<div class="main">
<?php if($yellow->page->isExisting("titleEvents")): ?>
<h1><?php echo $yellow->page->getHtml("titleEvents") ?></h1>
<?php endif ?>
<?php foreach($yellow->page->getPages() as $page): ?>
<?php $page->set("entryClass", "entry") ?>
<?php if($page->isExisting("tag")): ?>
<?php foreach(preg_split("/\s*,\s*/", $page->get("tag")) as $tag) { $page->set("entryClass", $page->get("entryClass")." tag-".$yellow->toolbox->normaliseArgs($tag, false)); } ?>
<?php endif ?>

<?php
	//Today's date
	$date_now = strtotime("now"); 
	//Events date
	$date_event_html = $page->getDateHtml("published");
	$date_event = strtotime($date_event_html); 
    if($date_event >= $date_now) 
		{
		echo "<div class=\"".$page->getHtml("entryClass")."\">";
		} 
	else 
		{
		echo "<div style=\"display:none\">";
		}; ?>

<div class="entry-title"><h3><a href="<?php echo $page->getLocation(true) ?>"><?php echo $page->getHtml("title") ?></a></h3></div>
<div class="entry-meta"><h2><?php echo $page->getDateHtml("published") ?> <?php echo $yellow->text->getHtml("eventsBy") ?> <?php $venueCounter = 0; foreach(preg_split("/\s*,\s*/", $page->get("venue")) as $venue) { if(++$venueCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getLocation(true).$yellow->toolbox->normaliseArgs("venue:$venue")."\">".htmlspecialchars($venue)."</a>"; } ?></h2></div>
<div class="entry-content"><?php echo $yellow->toolbox->createTextDescription($page->getContent(), 0, false, "<!--more-->", " <a href=\"".$page->getLocation(true)."\">".$yellow->text->getHtml("eventsMore")."</a><br><hr>") ?></div>
</div>
<?php endforeach ?>
<?php $yellow->snippet("pagination", $yellow->page->getPages()) ?>
</div>
</div>
