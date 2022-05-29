<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
    "PARAMETERS" => array(
        "INFOBLOCKID"   =>  array(
            "PARENT"    =>  "DATA_SOURCE",
            "NAME"      =>  GetMessage("T_DATA_SOURCE_PHR"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  array(
                "1" =>  "1",
                "2" =>  "23",
            ),
            "MULTIPLE"  =>  "N",
        ),
        "THEMES"    =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  GetMessage("T_THEME_PHR"),
            "TYPE"      =>  "STRING",
            "DEFAULT"   =>  "Значение по умолчанию"
        ),
        "MONTHS"    =>  array(
            "PARENT"    =>  "BASE",
            "NAME"      =>  GetMessage("T_MONTH_PHR"),
            "TYPE"      =>  "LIST",
            "VALUES"    =>  array(
                1  =>  "Январь",
                2  =>  "Февраль",
                3  =>  "Март",
                4  =>  "Апрель",
                5  =>  "Май",
                6  =>  "Июнь",
                7  =>  "Июль",
                8  =>  "Август",
                9  =>  "Сентябрь",
                10 =>  "Октябрь",
                11 =>  "Ноябрь",
                12 =>  "Декабрь",
            ),
            "MULTIPLE"  =>  "Y",
        ),
    )
);