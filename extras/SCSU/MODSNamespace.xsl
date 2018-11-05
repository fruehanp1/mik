<?xml version="1.0" encoding="UTF-8"?>
<!--Solution adapted from https://stackoverflow.com/q/15981488-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    exclude-result-prefixes="xs"
    version="2.0">
        <xsl:output indent="yes"/>
        <xsl:strip-space elements="*"/>
        
        <xsl:template match="@*|node()">
            <xsl:copy>
                <xsl:apply-templates select="@*|node()"/>
            </xsl:copy>
        </xsl:template>
        
        <xsl:template match="*" priority="1">
            <xsl:element name="mods:{local-name()}" namespace="http://www.loc.gov/mods/v3">
                <xsl:namespace name="xsi" select="'http://www.w3.org/2001/XMLSchema-instance'"/>
                <xsl:namespace name="xlink" select="'http://www.w3.org/1999/xlink'"/>
                <xsl:apply-templates select="@*|node()"/>
            </xsl:element>
        </xsl:template>
</xsl:stylesheet>