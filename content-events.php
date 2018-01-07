<div class="content">
<?php $yellow->snippet("sidebar") ?>
<div class="main">
<?php $yellow->page->set("entryClass", "entry") ?>
<?php if($yellow->page->isExisting("tag")): ?>
<?php foreach(preg_split("/\s*,\s*/", $yellow->page->get("tag")) as $tag) { $yellow->page->set("entryClass", $yellow->page->get("entryClass")." tag-".$yellow->toolbox->normaliseArgs($tag, false)); } ?>
<?php endif ?>
<div class="<?php echo $yellow->page->getHtml("entryClass") ?>">
<div class="entry-title"><h1><?php echo $yellow->page->getHtml("titleContent") ?></h1></div>
<div class="entry-meta"><p><?php echo $yellow->page->getDateHtml("published") ?> <?php echo $yellow->text->getHtml("eventsBy") ?> <?php $venueCounter = 0; foreach(preg_split("/\s*,\s*/", $yellow->page->get("venue")) as $venue) { if(++$venueCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("events")->getLocation(true).$yellow->toolbox->normaliseArgs("venue:$venue")."\">".htmlspecialchars($venue)."</a>"; } ?></p></div>
<div class="entry-content"><?php echo $yellow->page->getContent() ?></div>
<?php echo $yellow->page->getExtra("links") ?>
<?php if($yellow->page->isExisting("tag")): ?>
<div class="entry-tags">
<p><?php echo $yellow->text->getHtml("eventsTag") ?> <?php $tagCounter = 0; foreach(preg_split("/\s*,\s*/", $yellow->page->get("tag")) as $tag) { if(++$tagCounter>1) echo ", "; echo "<a href=\"".$yellow->page->getPage("events")->getLocation(true).$yellow->toolbox->normaliseArgs("tag:$tag")."\">".htmlspecialchars($tag)."</a>"; } ?></p>
</div>
<?php endif ?>
<?php echo $yellow->page->getExtra("comments") ?>
</div>
</div>
</div>
