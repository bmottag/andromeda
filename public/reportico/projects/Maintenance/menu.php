<?php
namespace Reportico\Engine;

$menu_title = ReporticoApp::getConfig("project_title");

$dropdown_menu = array(
                    array ( 
                        "project" => "Maintenance",
                        "title" => "Maintenance",
                        "items" => array (
                            array ( "reportfile" => "Maintenance program.xml" ),
							array ( "reportfile" => "Maintenance expenses.xml" )
                            )
                        ),
                );

?>