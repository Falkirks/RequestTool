<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace requesttool;
class PluginDescription{
    private $name;
    private $main;
    private $api;
    private $depend = [];
    private $softDepend = [];
    private $loadBefore = [];
    private $version;
    private $commands = [];
    private $description = null;
    private $authors = [];
    private $website = null;
    private $prefix = null;
    private $order = 1;

    /**
     * @param string $yamlString
     */
    public function __construct($yamlString){
        $this->loadMap(yaml_parse($yamlString)); //TODO compile a binary with YAML
    }

    /**
     * @param array $plugin
     *
     * @throws \Exception
     */
    private function loadMap(array $plugin){
        $this->name = preg_replace("[^A-Za-z0-9 _.-]", "", $plugin["name"]);
        if($this->name === ""){
            throw new \Exception("Invalid PluginDescription name");
        }
        $this->name = str_replace(" ", "_", $this->name);
        $this->version = $plugin["version"];
        $this->main = $plugin["main"];
        $this->api = !is_array($plugin["api"]) ? array($plugin["api"]) : $plugin["api"];
        if(stripos($this->main, "pocketmine\\") === 0){
            trigger_error("Invalid PluginDescription main, cannot start within the PocketMine namespace", E_USER_ERROR);

            return;
        }

        if(isset($plugin["commands"]) and is_array($plugin["commands"])){
            $this->commands = $plugin["commands"];
        }

        if(isset($plugin["depend"])){
            $this->depend = (array) $plugin["depend"];
        }
        if(isset($plugin["softdepend"])){
            $this->softDepend = (array) $plugin["softdepend"];
        }
        if(isset($plugin["loadbefore"])){
            $this->loadBefore = (array) $plugin["loadbefore"];
        }

        if(isset($plugin["website"])){
            $this->website = $plugin["website"];
        }
        if(isset($plugin["description"])){
            $this->description = $plugin["description"];
        }
        if(isset($plugin["prefix"])){
            $this->prefix = $plugin["prefix"];
        }
        if(isset($plugin["load"])){
            $order = strtoupper($plugin["load"]);
            if($order == "STARTUP") $this->order = 0;
            else $this->order = 1;
        }
        $this->authors = [];
        if(isset($plugin["author"])){
            $this->authors[] = $plugin["author"];
        }
        if(isset($plugin["authors"])){
            foreach($plugin["authors"] as $author){
                $this->authors[] = $author;
            }
        }
    }

    /**
     * @return string
     */
    public function getFullName(){
        return $this->name . " v" . $this->version;
    }

    /**
     * @return array
     */
    public function getCompatibleApis(){
        return $this->api;
    }

    /**
     * @return array
     */
    public function getAuthors(){
        return $this->authors;
    }

    /**
     * @return string
     */
    public function getPrefix(){
        return $this->prefix;
    }

    /**
     * @return array
     */
    public function getCommands(){
        return $this->commands;
    }

    /**
     * @return array
     */
    public function getDepend(){
        return $this->depend;
    }

    /**
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * @return array
     */
    public function getLoadBefore(){
        return $this->loadBefore;
    }

    /**
     * @return string
     */
    public function getMain(){
        return $this->main;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOrder(){
        return $this->order;
    }
    /**
     * @return array
     */
    public function getSoftDepend(){
        return $this->softDepend;
    }

    /**
     * @return string
     */
    public function getVersion(){
        return $this->version;
    }

    /**
     * @return string
     */
    public function getWebsite(){
        return $this->website;
    }
}