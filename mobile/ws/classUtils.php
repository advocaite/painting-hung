<?php

// **********************************************
// Webservice Demo by Elmü (www.netcult.ch/elmue)
// **********************************************

// To understand XPath queries read this: http://www.w3schools.com/XPath/xpath_syntax.asp
// Note that XML is case sensitive !!
class Utils
    {
    // retrieve a XML node's value
    public static function GetValue($XPath, $Path)
        {
        if (empty($XPath))
            return "";

        $Nodes=$XPath->query($Path);

        if (empty($Nodes) || $Nodes->length == 0)
            return "";

        return $Nodes->item(0)->nodeValue;
        }

    // retrieve a XML node's atrribute value
    public static function GetAttrib($XPath, $Path, $AttrName)
        {
        if (empty($XPath))
            return "";

        $Nodes=$XPath->query($Path);

        if (empty($Nodes) || $Nodes->length == 0)
            return "";

        return $Nodes->item(0)->getAttribute($AttrName);
        }

    // returns the URL in which the current PHP script is running
    public static function GetBaseUrl()
        {
        $Self=$_SERVER["PHP_SELF"];
        $Pos =strrpos($Self, "/");

        return "http://localhost" . substr($Self, 0, $Pos + 1);
        }
    }
?>