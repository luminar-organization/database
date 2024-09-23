<?php

namespace Luminar\Database\ORM;

class TypesAttributes
{
    // Text
    public const TYPE_VARCHAR = "varchar";
    public const TYPE_TEXT = "text";
    public const TYPE_TINYTEXT = "tinytext";
    public const TYPE_MEDIUMTEXT = "mediumtext";
    public const TYPE_LONGTEXT = "longtext";
    public const TYPE_CHAR = "char";
    public const TYPE_JSON = "json";

    // Real
    public const TYPE_FLOAT = "float";
    public const TYPE_DOUBLE = "double";
    public const TYPE_DECIMAL = "decimal";

    // Integer
    public const TYPE_INT = "int";
    public const TYPE_TINYINT = "tinyint";
    public const TYPE_SMALLINT = "smallint";
    public const TYPE_MEDIUMINT = "mediumint";
    public const TYPE_BIGINT = "bigint";
    public const TYPE_BIT = "bit";

    // Binary
    public const TYPE_BINARY = "binary";
    public const TYPE_VARBINARY = "varbinary";
    public const TYPE_TINYBLOB = "tinyblob";
    public const TYPE_BLOB = "blob";
    public const TYPE_MEDIUMBLOB = "mediumblob";
    public const TYPE_LONGBLOB = "longblob";

    // Temporal time
    public const TYPE_DATE = "date";
    public const TYPE_TIME = "time";
    public const TYPE_YEAR = "year";
    public const TYPE_DATETIME = "datetime";
    public const TYPE_TIMESTAMP = "timestamp";

    // Spatial geometry
    public const TYPE_POINT = "point";
    public const TYPE_LINESTRING = "linestring";
    public const TYPE_POLYGON = "polygon";
    public const TYPE_GEOMETRY = "geometry";
    public const TYPE_MULTIPOINT = "multipoint";
    public const TYPE_MULTILINESTRING = "multilinestring";
    public const TYPE_MULTIPOLYGON = "multipolygon";
    public const TYPE_GEOMETRYCOLLECTION = "geometrycollection";

    // Others
    public const TYPE_UNKNOWN = "unknown";
    public const TYPE_ENUM = "enum";
    public const TYPE_SET = "set";
}