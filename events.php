<?php
// Events plugin, https://github.com/datenstrom/yellow-plugins/tree/master/events
// Copyright (c) 2013-2017 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowEvents
{
	const VERSION = "0.1.0";
	var $yellow;			//access to API
	
	// Handle initialisation
	function onLoad($yellow)
	{
		$this->yellow = $yellow;
		$this->yellow->config->setDefault("eventsLocation", "");
		$this->yellow->config->setDefault("eventsNewLocation", "@title");
		$this->yellow->config->setDefault("eventsPagesMax", "10");
		$this->yellow->config->setDefault("eventsPaginationLimit", "5");
	}
	
	// Handle page content parsing of custom block
	function onParseContentBlock($page, $name, $text, $shortcut)
	{
		$output = null;
		if($name=="eventsarchive" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("eventsLocation");
			if(strempty($pagesMax)) $pagesMax = $this->yellow->config->get("eventsPagesMax");			
			$events = $this->yellow->pages->find($location);
			$pages = $this->getEventsPages($location);
			$page->setLastModified($pages->getModified());
			$months = array();
			foreach($pages as $page) if(preg_match("/^(\d+\-\d+)\-/", $page->get("published"), $matches)) ++$months[$matches[1]];
			if(count($months))
			{
				if($pagesMax!=0) $months = array_slice($months, -$pagesMax);
				uksort($months, "strnatcasecmp");
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($months as $key=>$value)
				{
					$output .= "<li><a href=\"".$events->getLocation(true).$this->yellow->toolbox->normaliseArgs("published:$key")."\">";
					$output .= htmlspecialchars($this->yellow->text->normaliseDate($key))."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$page->error(500, "Eventsarchive '$location' does not exist!");
			}
		}
		if($name=="eventsvenues" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("eventsLocation");
			if(strempty($pagesMax)) $pagesMax = $this->yellow->config->get("eventsPagesMax");
			$events = $this->yellow->pages->find($location);
			$pages = $this->getEventsPages($location);
			$page->setLastModified($pages->getModified());
			$venues = array();
			foreach($pages as $page) if($page->isExisting("venue")) foreach(preg_split("/\s*,\s*/", $page->get("venue")) as $venue) ++$venues[$venue];
			if(count($venues))
			{
				$venues = $this->yellow->lookup->normaliseUpperLower($venues);
				if($pagesMax!=0 && count($venues)>$pagesMax)
				{
					uasort($venues, "strnatcasecmp");
					$venues = array_slice($venues, -$pagesMax);
				}
				uksort($venues, "strnatcasecmp");
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($venues as $key=>$value)
				{
					$output .= "<li><a href=\"".$events->getLocation(true).$this->yellow->toolbox->normaliseArgs("venue:$key")."\">";
					$output .= htmlspecialchars($key)."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$page->error(500, "Eventsvenues '$location' does not exist!");
			}
		}
		if($name=="eventsrecent" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("eventsLocation");
			if(strempty($pagesMax)) $pagesMax = $this->yellow->config->get("eventsPagesMax");
			$events = $this->yellow->pages->find($location);
			$pages = $this->getEventsPages($location);
			$pages->sort("published", true);
			$page->setLastModified($pages->getModified());
			if(count($pages))
			{
				if($pagesMax!=0) $pages->limit($pagesMax);
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($pages as $page)
				{
				//Today's date
				$date_now = strtotime("now"); 
				//Events date
				$date_event_html = $page->getDateHtml("published");
				$date_event = strtotime($date_event_html); 
				if($date_event >= $date_now) 
				{
				$output .= "<li><a href=\"".$page->getLocation(true)."\">".$page->getHtml("titleNavigation")."</a></li>\n";
				};
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$page->error(500, "Eventsrecent '$location' does not exist!");
			}
		}
		if($name=="eventsrelated" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("eventsLocation");
			if(strempty($pagesMax)) $pagesMax = $this->yellow->config->get("eventsPagesMax");
			$events = $this->yellow->pages->find($location);
			$pages = $this->getEventsPages($location);
			$pages->similar($page->getPage("main"));
			$page->setLastModified($pages->getModified());
			if(count($pages))
			{
				if($pagesMax!=0) $pages->limit($pagesMax);
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($pages as $page)
				{
					$output .= "<li><a href=\"".$page->getLocation(true)."\">".$page->getHtml("titleNavigation")."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$page->error(500, "Eventsrelated '$location' does not exist!");
			}
		}
		if($name=="eventstags" && $shortcut)
		{
			list($location, $pagesMax) = $this->yellow->toolbox->getTextArgs($text);
			if(empty($location)) $location = $this->yellow->config->get("eventsLocation");
			if(strempty($pagesMax)) $pagesMax = $this->yellow->config->get("eventsPagesMax");
			$events = $this->yellow->pages->find($location);
			$pages = $this->getEventsPages($location);
			$page->setLastModified($pages->getModified());
			$tags = array();
			foreach($pages as $page) if($page->isExisting("tag")) foreach(preg_split("/\s*,\s*/", $page->get("tag")) as $tag) ++$tags[$tag];
			if(count($tags))
			{
				$tags = $this->yellow->lookup->normaliseUpperLower($tags);
				if($pagesMax!=0 && count($tags)>$pagesMax)
				{
					uasort($tags, "strnatcasecmp");
					$tags = array_slice($tags, -$pagesMax);
				}
				uksort($tags, "strnatcasecmp");
				$output = "<div class=\"".htmlspecialchars($name)."\">\n";
				$output .= "<ul>\n";
				foreach($tags as $key=>$value)
				{
					$output .= "<li><a href=\"".$events->getLocation(true).$this->yellow->toolbox->normaliseArgs("tag:$key")."\">";
					$output .= htmlspecialchars($key)."</a></li>\n";
				}
				$output .= "</ul>\n";
				$output .= "</div>\n";
			} else {
				$page->error(500, "Eventstags '$location' does not exist!");
			}
		}
		return $output;
	}
	
	// Handle page parsing
	function onParsePage()
	{
		if($this->yellow->page->get("template")=="eventspages")
		{
			$pages = $this->getEventsPages($this->yellow->page->location);
			$pagesFilter = array();
			if($_REQUEST["tag"])
			{
				$pages->filter("tag", $_REQUEST["tag"]);
				array_push($pagesFilter, $pages->getFilter());
			}
			if($_REQUEST["venue"])
			{
				$pages->filter("venue", $_REQUEST["venue"]);
				array_push($pagesFilter, $pages->getFilter());
			}
			if($_REQUEST["published"])
			{
				$pages->filter("published", $_REQUEST["published"], false);
				array_push($pagesFilter, $this->yellow->text->normaliseDate($pages->getFilter()));
			}
			$pages->sort("published", false);
			$pages->pagination($this->yellow->config->get("eventsPaginationLimit"));
			if(!$pages->getPaginationNumber()) $this->yellow->page->error(404);
			if(!empty($pagesFilter))
			{
				$title = implode(' ', $pagesFilter);
				$this->yellow->page->set("titleHeader", $title." - ".$this->yellow->page->get("sitename"));
				$this->yellow->page->set("titleEvents", $this->yellow->text->get("eventsFilter")." ".$title);
			}
			$this->yellow->page->setPages($pages);
			$this->yellow->page->setLastModified($pages->getModified());
			$this->yellow->page->setHeader("Cache-Control", "max-age=60");
		}
		if($this->yellow->page->get("template")=="events")
		{
			$location = $this->yellow->config->get("eventsLocation");
			if(empty($location)) $location = $this->yellow->lookup->getDirectoryLocation($this->yellow->page->location);
			$events = $this->yellow->pages->find($location);
			$this->yellow->page->setPage("events", $events);
		}
	}
	
	// Handle content file editing
	function onEditContentFile($page, $action)
	{
		if($page->get("template")=="events") $page->set("pageNewLocation", $this->yellow->config->get("eventsNewLocation"));
	}

	// Return events pages
	function getEventsPages($location)
	{
		$pages = $this->yellow->pages->clean();
		$events = $this->yellow->pages->find($location);
		if($events)
		{
			if($location==$this->yellow->config->get("eventsLocation"))
			{
				$pages = $this->yellow->pages->index(!$events->isVisible());
			} else {
				$pages = $events->getChildren(!$events->isVisible());
			}
			$pages->filter("template", "events");
		}
		return $pages;
	}
}

$yellow->plugins->register("events", "YellowEvents", YellowEvents::VERSION);
?>
