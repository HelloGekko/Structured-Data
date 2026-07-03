<?php
/**
 * Auto-generated schema.org property catalog. DO NOT EDIT BY HAND.
 * Generated from the official schema.org vocabulary by bin/generate-catalog.php.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

return array (
  'version' => '30.0',
  'types' => 
  array (
    'Article' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'articleBody' => 
      array (
        'label' => 'Article Body',
        'type' => 'text',
        'comment' => 'The actual body of the article.',
      ),
      'articleSection' => 
      array (
        'label' => 'Article Section',
        'type' => 'text',
        'comment' => 'Articles may belong to one or more \'sections\' in a magazine or newspaper, such as Sports, Lifestyle, etc.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'backstory' => 
      array (
        'label' => 'Backstory',
        'type' => 'text',
        'comment' => 'For an Article, typically a NewsArticle, the backstory property provides a textual summary giving a brief explanation of why and how an article was created. In a journalistic setting this could include information about reporting process, methods, interviews, data sources, etc.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pageEnd' => 
      array (
        'label' => 'Page End',
        'type' => 'number',
        'comment' => 'The page on which the work ends; for example "138" or "xvi".',
      ),
      'pageStart' => 
      array (
        'label' => 'Page Start',
        'type' => 'number',
        'comment' => 'The page on which the work starts; for example "135" or "xiii".',
      ),
      'pagination' => 
      array (
        'label' => 'Pagination',
        'type' => 'text',
        'comment' => 'Any description of pages that is not separated into pageStart and pageEnd; for example, "1-6, 9, 55" or "10-12, 46-49".',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'BlogPosting' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'articleBody' => 
      array (
        'label' => 'Article Body',
        'type' => 'text',
        'comment' => 'The actual body of the article.',
      ),
      'articleSection' => 
      array (
        'label' => 'Article Section',
        'type' => 'text',
        'comment' => 'Articles may belong to one or more \'sections\' in a magazine or newspaper, such as Sports, Lifestyle, etc.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'backstory' => 
      array (
        'label' => 'Backstory',
        'type' => 'text',
        'comment' => 'For an Article, typically a NewsArticle, the backstory property provides a textual summary giving a brief explanation of why and how an article was created. In a journalistic setting this could include information about reporting process, methods, interviews, data sources, etc.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pageEnd' => 
      array (
        'label' => 'Page End',
        'type' => 'number',
        'comment' => 'The page on which the work ends; for example "138" or "xvi".',
      ),
      'pageStart' => 
      array (
        'label' => 'Page Start',
        'type' => 'number',
        'comment' => 'The page on which the work starts; for example "135" or "xiii".',
      ),
      'pagination' => 
      array (
        'label' => 'Pagination',
        'type' => 'text',
        'comment' => 'Any description of pages that is not separated into pageStart and pageEnd; for example, "1-6, 9, 55" or "10-12, 46-49".',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'Book' => 
    array (
      'abridged' => 
      array (
        'label' => 'Abridged',
        'type' => 'boolean',
        'comment' => 'Indicates whether the book is an abridged edition.',
      ),
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'bookEdition' => 
      array (
        'label' => 'Book Edition',
        'type' => 'text',
        'comment' => 'The edition of the book.',
      ),
      'bookFormat' => 
      array (
        'label' => 'Book Format',
        'type' => 'enum',
        'comment' => 'The format of the book.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AudiobookFormat',
            'label' => 'AudiobookFormat',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/EBook',
            'label' => 'EBook',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/GraphicNovel',
            'label' => 'GraphicNovel',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/Hardcover',
            'label' => 'Hardcover',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/Pamphlet',
            'label' => 'Pamphlet',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/Paperback',
            'label' => 'Paperback',
          ),
        ),
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'isbn' => 
      array (
        'label' => 'Isbn',
        'type' => 'text',
        'comment' => 'The ISBN of the book.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'numberOfPages' => 
      array (
        'label' => 'Number Of Pages',
        'type' => 'number',
        'comment' => 'The number of pages in the book.',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'FAQPage' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'breadcrumb' => 
      array (
        'label' => 'Breadcrumb',
        'type' => 'text',
        'comment' => 'A set of links that can help a user understand and navigate a website hierarchy.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'lastReviewed' => 
      array (
        'label' => 'Last Reviewed',
        'type' => 'date',
        'comment' => 'Date on which the content on this web page was last reviewed for accuracy and/or completeness.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'relatedLink' => 
      array (
        'label' => 'Related Link',
        'type' => 'url',
        'comment' => 'A link related to this web page, for example to other related web pages.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'significantLink' => 
      array (
        'label' => 'Significant Link',
        'type' => 'url',
        'comment' => 'One of the more significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'significantLinks' => 
      array (
        'label' => 'Significant Links',
        'type' => 'url',
        'comment' => 'The most significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'NewsArticle' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'articleBody' => 
      array (
        'label' => 'Article Body',
        'type' => 'text',
        'comment' => 'The actual body of the article.',
      ),
      'articleSection' => 
      array (
        'label' => 'Article Section',
        'type' => 'text',
        'comment' => 'Articles may belong to one or more \'sections\' in a magazine or newspaper, such as Sports, Lifestyle, etc.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'backstory' => 
      array (
        'label' => 'Backstory',
        'type' => 'text',
        'comment' => 'For an Article, typically a NewsArticle, the backstory property provides a textual summary giving a brief explanation of why and how an article was created. In a journalistic setting this could include information about reporting process, methods, interviews, data sources, etc.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'dateline' => 
      array (
        'label' => 'Dateline',
        'type' => 'text',
        'comment' => 'A [dateline](https://en.wikipedia.org/wiki/Dateline) is a brief piece of text included in news articles that describes where and when the story was written or filed though the date is often omitted. Sometimes only a placename is provided. Structured representations of dateline-related information can also be expressed more explicitly using locationCreated (which represents where a work was created, e.g. where a news report was written). For location depicted or described in the content, use contentLocation. Dateline summaries are oriented more towards human readers than towards automated processing, and can vary substantially. Some examples: "BEIRUT, Lebanon, June 2.", "Paris, France", "December 19, 2017 11:43AM Reporting from Washington", "Beijing/Moscow", "QUEZON CITY, Philippines".',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pageEnd' => 
      array (
        'label' => 'Page End',
        'type' => 'number',
        'comment' => 'The page on which the work ends; for example "138" or "xvi".',
      ),
      'pageStart' => 
      array (
        'label' => 'Page Start',
        'type' => 'number',
        'comment' => 'The page on which the work starts; for example "135" or "xiii".',
      ),
      'pagination' => 
      array (
        'label' => 'Pagination',
        'type' => 'text',
        'comment' => 'Any description of pages that is not separated into pageStart and pageEnd; for example, "1-6, 9, 55" or "10-12, 46-49".',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'printColumn' => 
      array (
        'label' => 'Print Column',
        'type' => 'text',
        'comment' => 'The number of the column in which the NewsArticle appears in the print edition.',
      ),
      'printEdition' => 
      array (
        'label' => 'Print Edition',
        'type' => 'text',
        'comment' => 'The edition of the print product in which the NewsArticle appears.',
      ),
      'printPage' => 
      array (
        'label' => 'Print Page',
        'type' => 'text',
        'comment' => 'If this NewsArticle appears in print, this field indicates the name of the page on which the article is found. Please note that this field is intended for the exact page name (e.g. A5, B18).',
      ),
      'printSection' => 
      array (
        'label' => 'Print Section',
        'type' => 'text',
        'comment' => 'If this NewsArticle appears in print, this field indicates the print section in which the article appeared.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'WebPage' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'breadcrumb' => 
      array (
        'label' => 'Breadcrumb',
        'type' => 'text',
        'comment' => 'A set of links that can help a user understand and navigate a website hierarchy.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'lastReviewed' => 
      array (
        'label' => 'Last Reviewed',
        'type' => 'date',
        'comment' => 'Date on which the content on this web page was last reviewed for accuracy and/or completeness.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'relatedLink' => 
      array (
        'label' => 'Related Link',
        'type' => 'url',
        'comment' => 'A link related to this web page, for example to other related web pages.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'significantLink' => 
      array (
        'label' => 'Significant Link',
        'type' => 'url',
        'comment' => 'One of the more significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'significantLinks' => 
      array (
        'label' => 'Significant Links',
        'type' => 'url',
        'comment' => 'The most significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'ItemPage' => 
    array (
      'abstract' => 
      array (
        'label' => 'Abstract',
        'type' => 'text',
        'comment' => 'An abstract is a short description that summarizes a CreativeWork.',
      ),
      'accessMode' => 
      array (
        'label' => 'Access Mode',
        'type' => 'text',
        'comment' => 'The human sensory perceptual system or cognitive faculty through which a person may process or perceive the intellectual content of a resource, not including any adaptations of the content (e.g., text alternatives for images). Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessMode-vocabulary).',
      ),
      'accessibilityAPI' => 
      array (
        'label' => 'Accessibility A P I',
        'type' => 'text',
        'comment' => 'Indicates that the resource is compatible with the referenced accessibility API. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityAPI-vocabulary).',
      ),
      'accessibilityControl' => 
      array (
        'label' => 'Accessibility Control',
        'type' => 'text',
        'comment' => 'Identifies input methods that are sufficient to fully control the described resource. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityControl-vocabulary).',
      ),
      'accessibilityFeature' => 
      array (
        'label' => 'Accessibility Feature',
        'type' => 'text',
        'comment' => 'Content features of the resource, such as accessible media, alternatives and supported enhancements for accessibility. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityFeature-vocabulary).',
      ),
      'accessibilityHazard' => 
      array (
        'label' => 'Accessibility Hazard',
        'type' => 'text',
        'comment' => 'A characteristic of the described resource that is physiologically dangerous to some users. Related to WCAG 2.0 guideline 2.3. Values should be drawn from the [approved vocabulary](https://www.w3.org/2021/a11y-discov-vocab/latest/#accessibilityHazard-vocabulary).',
      ),
      'accessibilitySummary' => 
      array (
        'label' => 'Accessibility Summary',
        'type' => 'text',
        'comment' => 'A human-readable summary of specific accessibility features or deficiencies, consistent with the other accessibility metadata but expressing subtleties such as "short descriptions are present but long descriptions will be needed for non-visual users" or "short descriptions are present and no long descriptions are needed".',
      ),
      'acquireLicensePage' => 
      array (
        'label' => 'Acquire License Page',
        'type' => 'url',
        'comment' => 'Indicates a page documenting how licenses can be purchased or otherwise acquired, for the current item.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'alternativeHeadline' => 
      array (
        'label' => 'Alternative Headline',
        'type' => 'text',
        'comment' => 'A secondary title of the CreativeWork.',
      ),
      'archivedAt' => 
      array (
        'label' => 'Archived At',
        'type' => 'url',
        'comment' => 'Indicates a page or other link involved in archival of a CreativeWork. In the case of MediaReview, the items in a MediaReviewItem may often become inaccessible, but be archived by archival, journalistic, activist, or law enforcement organizations. In such cases, the referenced page may not directly publish the content.',
      ),
      'assesses' => 
      array (
        'label' => 'Assesses',
        'type' => 'text',
        'comment' => 'The item being described is intended to assess the competency or learning outcome defined by the referenced term.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'breadcrumb' => 
      array (
        'label' => 'Breadcrumb',
        'type' => 'text',
        'comment' => 'A set of links that can help a user understand and navigate a website hierarchy.',
      ),
      'citation' => 
      array (
        'label' => 'Citation',
        'type' => 'text',
        'comment' => 'A citation or reference to another creative work, such as another publication, web page, scholarly article, etc.',
      ),
      'commentCount' => 
      array (
        'label' => 'Comment Count',
        'type' => 'number',
        'comment' => 'The number of comments this CreativeWork (e.g. Article, Question or Answer) has received. This is most applicable to works published in Web sites with commenting system; additional comments may exist elsewhere.',
      ),
      'conditionsOfAccess' => 
      array (
        'label' => 'Conditions Of Access',
        'type' => 'text',
        'comment' => 'Conditions that affect the availability of, or method(s) of access to, an item. Typically used for real world items such as an ArchiveComponent held by an ArchiveOrganization. This property is not suitable for use as a general Web access control mechanism. It is expressed only in natural language.\\n\\nFor example "Available by appointment from the Reading Room" or "Accessible only from logged-in accounts ".',
      ),
      'contentRating' => 
      array (
        'label' => 'Content Rating',
        'type' => 'text',
        'comment' => 'Official rating of a piece of content&#x2014;for example, \'MPAA PG-13\'.',
      ),
      'contentReferenceTime' => 
      array (
        'label' => 'Content Reference Time',
        'type' => 'date',
        'comment' => 'The specific time described by a creative work, for works (e.g. articles, video objects etc.) that emphasise a particular moment within an Event.',
      ),
      'copyrightNotice' => 
      array (
        'label' => 'Copyright Notice',
        'type' => 'text',
        'comment' => 'Text of a notice appropriate for describing the copyright aspects of this Creative Work, ideally indicating the owner of the copyright for the Work.',
      ),
      'copyrightYear' => 
      array (
        'label' => 'Copyright Year',
        'type' => 'number',
        'comment' => 'The year during which the claimed copyright for the CreativeWork was first asserted.',
      ),
      'correction' => 
      array (
        'label' => 'Correction',
        'type' => 'text',
        'comment' => 'Indicates a correction to a CreativeWork, either via a CorrectionComment, textually or in another document.',
      ),
      'creativeWorkStatus' => 
      array (
        'label' => 'Creative Work Status',
        'type' => 'text',
        'comment' => 'The status of a creative work in terms of its stage in a lifecycle. Example terms include Incomplete, Draft, Published, Obsolete. Some organizations define a set of terms for the stages of their publication lifecycle.',
      ),
      'creditText' => 
      array (
        'label' => 'Credit Text',
        'type' => 'text',
        'comment' => 'Text that can be used to credit person(s) and/or organization(s) associated with a published Creative Work.',
      ),
      'dateCreated' => 
      array (
        'label' => 'Date Created',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was created or the item was added to a DataFeed.',
      ),
      'dateModified' => 
      array (
        'label' => 'Date Modified',
        'type' => 'date',
        'comment' => 'The date on which the CreativeWork was most recently modified or when the item\'s entry was modified within a DataFeed.',
      ),
      'datePublished' => 
      array (
        'label' => 'Date Published',
        'type' => 'date',
        'comment' => 'Date of first publication or broadcast. For example the date a CreativeWork was broadcast or a Certification was issued.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'digitalSourceType' => 
      array (
        'label' => 'Digital Source Type',
        'type' => 'enum',
        'comment' => 'Indicates an IPTCDigitalSourceEnumeration code indicating the nature of the digital source(s) for some CreativeWork.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
            'label' => 'AlgorithmicMediaDigitalSource',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
            'label' => 'AlgorithmicallyEnhancedDigitalSource',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/CompositeCaptureDigitalSource',
            'label' => 'CompositeCaptureDigitalSource',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/CompositeDigitalSource',
            'label' => 'CompositeDigitalSource',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
            'label' => 'CompositeSyntheticDigitalSource',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
            'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
            'label' => 'DataDrivenMediaDigitalSource',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/DigitalArtDigitalSource',
            'label' => 'DigitalArtDigitalSource',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/DigitalCaptureDigitalSource',
            'label' => 'DigitalCaptureDigitalSource',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
            'label' => 'MinorHumanEditsDigitalSource',
          ),
          10 => 
          array (
            'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
            'label' => 'MultiFrameComputationalCaptureDigitalSource',
          ),
          11 => 
          array (
            'value' => 'https://schema.org/NegativeFilmDigitalSource',
            'label' => 'NegativeFilmDigitalSource',
          ),
          12 => 
          array (
            'value' => 'https://schema.org/PositiveFilmDigitalSource',
            'label' => 'PositiveFilmDigitalSource',
          ),
          13 => 
          array (
            'value' => 'https://schema.org/PrintDigitalSource',
            'label' => 'PrintDigitalSource',
          ),
          14 => 
          array (
            'value' => 'https://schema.org/ScreenCaptureDigitalSource',
            'label' => 'ScreenCaptureDigitalSource',
          ),
          15 => 
          array (
            'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
            'label' => 'TrainedAlgorithmicMediaDigitalSource',
          ),
          16 => 
          array (
            'value' => 'https://schema.org/VirtualRecordingDigitalSource',
            'label' => 'VirtualRecordingDigitalSource',
          ),
        ),
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'discussionUrl' => 
      array (
        'label' => 'Discussion Url',
        'type' => 'url',
        'comment' => 'A link to the page containing the comments of the CreativeWork.',
      ),
      'editEIDR' => 
      array (
        'label' => 'Edit E I D R',
        'type' => 'text',
        'comment' => 'An [EIDR](https://eidr.org/) (Entertainment Identifier Registry) identifier representing a specific edit / edition for a work of film or television. For example, the motion picture known as "Ghostbusters" whose titleEIDR is "10.5240/7EC7-228A-510A-053E-CBB8-J" has several edits, e.g. "10.5240/1F2A-E1C5-680A-14C6-E76B-I" and "10.5240/8A35-3BEE-6497-5D12-9E4F-3". Since schema.org types like Movie and TVEpisode can be used for both works and their multiple expressions, it is possible to use titleEIDR alone (for a general description), or alongside editEIDR for a more edit-specific description.',
      ),
      'educationalLevel' => 
      array (
        'label' => 'Educational Level',
        'type' => 'text',
        'comment' => 'The level in terms of progression through an educational or training context. Examples of educational levels include \'beginner\', \'intermediate\' or \'advanced\', and formal sets of level indicators.',
      ),
      'educationalUse' => 
      array (
        'label' => 'Educational Use',
        'type' => 'text',
        'comment' => 'The purpose of a work in the context of education; for example, \'assignment\', \'group work\'.',
      ),
      'encodingFormat' => 
      array (
        'label' => 'Encoding Format',
        'type' => 'text',
        'comment' => 'Media type typically expressed using a MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml) and [MDN reference](https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types)), e.g. application/zip for a SoftwareApplication binary, audio/mpeg for .mp3 etc. In cases where a CreativeWork has several media type representations, encoding can be used to indicate each MediaObject alongside particular encodingFormat information. Unregistered or niche encoding and file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia/Wikidata entry.',
      ),
      'expires' => 
      array (
        'label' => 'Expires',
        'type' => 'date',
        'comment' => 'Date the content expires and is no longer useful or available. For example a VideoObject or NewsArticle whose availability or relevance is time-limited, a ClaimReview fact check whose publisher wants to indicate that it may no longer be relevant (or helpful to highlight) after some date, or a Certification the validity has expired.',
      ),
      'fileFormat' => 
      array (
        'label' => 'File Format',
        'type' => 'text',
        'comment' => 'Media type, typically MIME format (see [IANA site](http://www.iana.org/assignments/media-types/media-types.xhtml)) of the content, e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, \'encoding\' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia entry.',
      ),
      'genre' => 
      array (
        'label' => 'Genre',
        'type' => 'text',
        'comment' => 'Genre of the creative work, broadcast channel or group.',
      ),
      'headline' => 
      array (
        'label' => 'Headline',
        'type' => 'text',
        'comment' => 'Headline of the article.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'interactivityType' => 
      array (
        'label' => 'Interactivity Type',
        'type' => 'text',
        'comment' => 'The predominant mode of learning supported by the learning resource. Acceptable values are \'active\', \'expositive\', or \'mixed\'.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isBasedOn' => 
      array (
        'label' => 'Is Based On',
        'type' => 'url',
        'comment' => 'A resource from which this work is derived or from which it is a modification or adaptation.',
      ),
      'isBasedOnUrl' => 
      array (
        'label' => 'Is Based On Url',
        'type' => 'url',
        'comment' => 'A resource that was used in the creation of this resource. This term can be repeated for multiple sources. For example, http://example.com/great-multiplication-intro.html.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'isPartOf' => 
      array (
        'label' => 'Is Part Of',
        'type' => 'url',
        'comment' => 'Indicates an item or CreativeWork that this item, or CreativeWork (in some sense), is part of.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'lastReviewed' => 
      array (
        'label' => 'Last Reviewed',
        'type' => 'date',
        'comment' => 'Date on which the content on this web page was last reviewed for accuracy and/or completeness.',
      ),
      'learningResourceType' => 
      array (
        'label' => 'Learning Resource Type',
        'type' => 'text',
        'comment' => 'The predominant type or kind characterizing the learning resource. For example, \'presentation\', \'handout\'.',
      ),
      'license' => 
      array (
        'label' => 'License',
        'type' => 'url',
        'comment' => 'A license document that applies to this content, typically indicated by URL.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'materialExtent' => 
      array (
        'label' => 'Material Extent',
        'type' => 'text',
        'comment' => 'The quantity of the materials being described or an expression of the physical space they occupy.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'position' => 
      array (
        'label' => 'Position',
        'type' => 'number',
        'comment' => 'The position of an item in a series or sequence of items.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'relatedLink' => 
      array (
        'label' => 'Related Link',
        'type' => 'url',
        'comment' => 'A link related to this web page, for example to other related web pages.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'schemaVersion' => 
      array (
        'label' => 'Schema Version',
        'type' => 'text',
        'comment' => 'Indicates (by URL or string) a particular version of a schema used in some CreativeWork. This property was created primarily to indicate the use of a specific schema.org release, e.g. ```10.0``` as a simple string, or more explicitly via URL, ```https://schema.org/docs/releases.html#v10.0```. There may be situations in which other schemas might usefully be referenced this way, e.g. ```http://dublincore.org/specifications/dublin-core/dces/1999-07-02/``` but this has not been carefully explored in the community.',
      ),
      'sdDatePublished' => 
      array (
        'label' => 'Sd Date Published',
        'type' => 'date',
        'comment' => 'Indicates the date on which the current structured data was generated / published. Typically used alongside sdPublisher.',
      ),
      'sdLicense' => 
      array (
        'label' => 'Sd License',
        'type' => 'url',
        'comment' => 'A license document that applies to this structured data, typically indicated by URL.',
      ),
      'significantLink' => 
      array (
        'label' => 'Significant Link',
        'type' => 'url',
        'comment' => 'One of the more significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'significantLinks' => 
      array (
        'label' => 'Significant Links',
        'type' => 'url',
        'comment' => 'The most significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'speakable' => 
      array (
        'label' => 'Speakable',
        'type' => 'url',
        'comment' => 'Indicates sections of a Web page that are particularly \'speakable\' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the \'speakable\' property serves to indicate the parts most likely to be generally useful for speech. The *speakable* property can be repeated an arbitrary number of times, with three kinds of possible \'content-locator\' values: 1.) *id-value* URL references - uses *id-value* of an element in the page being annotated. The simplest use of *speakable* has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, e.g. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the *speakable* property.',
      ),
      'teaches' => 
      array (
        'label' => 'Teaches',
        'type' => 'text',
        'comment' => 'The item being described is intended to help a person learn the competency or learning outcome defined by the referenced term.',
      ),
      'temporal' => 
      array (
        'label' => 'Temporal',
        'type' => 'date',
        'comment' => 'The "temporal" property can be used in cases where more specific properties (e.g. temporalCoverage, dateCreated, dateModified, datePublished) are not known to be appropriate.',
      ),
      'temporalCoverage' => 
      array (
        'label' => 'Temporal Coverage',
        'type' => 'date',
        'comment' => 'The temporalCoverage of a CreativeWork indicates the period that the content applies to, i.e. that it describes, either as a DateTime or as a textual string indicating a time period in [ISO 8601 time interval format](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals). In the case of a Dataset it will typically indicate the relevant time period in a precise notation (e.g. for a 2011 census dataset, the year 2011 would be written "2011/2012"). Other forms of content, e.g. ScholarlyArticle, Book, TVSeries or TVEpisode, may indicate their temporalCoverage in broader terms - textually or via well-known URL. Written works such as books may sometimes have precise temporal coverage too, e.g. a work set in 1939 - 1945 can be indicated in ISO 8601 interval format format via "1939/1945". Open-ended date ranges can be written with ".." in place of the end date. For example, "2015-11/.." indicates a range beginning in November 2015 and with no specified final date. This is tentative and might be updated in future when ISO 8601 is officially updated.',
      ),
      'text' => 
      array (
        'label' => 'Text',
        'type' => 'text',
        'comment' => 'The textual content of this CreativeWork.',
      ),
      'thumbnailUrl' => 
      array (
        'label' => 'Thumbnail Url',
        'type' => 'url',
        'comment' => 'A thumbnail image relevant to the Thing.',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'usageInfo' => 
      array (
        'label' => 'Usage Info',
        'type' => 'url',
        'comment' => 'The schema.org usageInfo property indicates further information about a CreativeWork. This property is applicable both to works that are freely available and to those that require payment or other transactions. It can reference additional information, e.g. community expectations on preferred linking and citation conventions, as well as purchasing details. For something that can be commercially licensed, usageInfo can provide detailed, resource-specific information about licensing options. This property can be used alongside the license property which indicates license(s) applicable to some piece of content. The usageInfo property can provide information about other licensing options, e.g. acquiring commercial usage rights for an image that is also available under non-commercial creative commons licenses.',
      ),
      'version' => 
      array (
        'label' => 'Version',
        'type' => 'number',
        'comment' => 'The version of the CreativeWork embodied by a specified resource.',
      ),
      'wordCount' => 
      array (
        'label' => 'Word Count',
        'type' => 'number',
        'comment' => 'The number of words in the text of the CreativeWork such as an Article, Book, etc.',
      ),
    ),
    'Event' => 
    array (
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'doorTime' => 
      array (
        'label' => 'Door Time',
        'type' => 'date',
        'comment' => 'The time admission will commence.',
      ),
      'endDate' => 
      array (
        'label' => 'End Date',
        'type' => 'date',
        'comment' => 'The end date and time of the item (in [ISO 8601 date format](http://en.wikipedia.org/wiki/ISO_8601)).',
      ),
      'eventAttendanceMode' => 
      array (
        'label' => 'Event Attendance Mode',
        'type' => 'enum',
        'comment' => 'The eventAttendanceMode of an event indicates whether it occurs online, offline, or a mix.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/MixedEventAttendanceMode',
            'label' => 'MixedEventAttendanceMode',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/OfflineEventAttendanceMode',
            'label' => 'OfflineEventAttendanceMode',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/OnlineEventAttendanceMode',
            'label' => 'OnlineEventAttendanceMode',
          ),
        ),
      ),
      'eventStatus' => 
      array (
        'label' => 'Event Status',
        'type' => 'enum',
        'comment' => 'An eventStatus of an event represents its status; particularly useful when an event is cancelled or rescheduled.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/EventCancelled',
            'label' => 'EventCancelled',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/EventMovedOnline',
            'label' => 'EventMovedOnline',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/EventPostponed',
            'label' => 'EventPostponed',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/EventRescheduled',
            'label' => 'EventRescheduled',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/EventScheduled',
            'label' => 'EventScheduled',
          ),
        ),
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inLanguage' => 
      array (
        'label' => 'In Language',
        'type' => 'text',
        'comment' => 'The language of the content or performance or used in an action. Please use one of the language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47). See also availableLanguage.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'location' => 
      array (
        'label' => 'Location',
        'type' => 'text',
        'comment' => 'The location of, for example, where an event is happening, where an organization is located, or where an action takes place.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'maximumAttendeeCapacity' => 
      array (
        'label' => 'Maximum Attendee Capacity',
        'type' => 'number',
        'comment' => 'The total number of individuals that may attend an event or venue.',
      ),
      'maximumPhysicalAttendeeCapacity' => 
      array (
        'label' => 'Maximum Physical Attendee Capacity',
        'type' => 'number',
        'comment' => 'The maximum physical attendee capacity of an Event whose eventAttendanceMode is OfflineEventAttendanceMode (or the offline aspects, in the case of a MixedEventAttendanceMode).',
      ),
      'maximumVirtualAttendeeCapacity' => 
      array (
        'label' => 'Maximum Virtual Attendee Capacity',
        'type' => 'number',
        'comment' => 'The maximum virtual attendee capacity of an Event whose eventAttendanceMode is OnlineEventAttendanceMode (or the online aspects, in the case of a MixedEventAttendanceMode).',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'previousStartDate' => 
      array (
        'label' => 'Previous Start Date',
        'type' => 'date',
        'comment' => 'Used in conjunction with eventStatus for rescheduled or cancelled events. This property contains the previously scheduled start date. For rescheduled events, the startDate property should be used for the newly scheduled start date. In the (rare) case of an event that has been postponed and rescheduled multiple times, this field may be repeated.',
      ),
      'remainingAttendeeCapacity' => 
      array (
        'label' => 'Remaining Attendee Capacity',
        'type' => 'number',
        'comment' => 'The number of attendee places for an event that remain unallocated.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'startDate' => 
      array (
        'label' => 'Start Date',
        'type' => 'date',
        'comment' => 'The start date and time of the item (in [ISO 8601 date format](http://en.wikipedia.org/wiki/ISO_8601)).',
      ),
      'typicalAgeRange' => 
      array (
        'label' => 'Typical Age Range',
        'type' => 'text',
        'comment' => 'The typical expected age range, e.g. \'7-9\', \'11-\'.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
    ),
    'LocalBusiness' => 
    array (
      'acceptedPaymentMethod' => 
      array (
        'label' => 'Accepted Payment Method',
        'type' => 'text',
        'comment' => 'The payment method(s) that are accepted in general by an organization, or for some specific demand or offer.',
      ),
      'actionableFeedbackPolicy' => 
      array (
        'label' => 'Actionable Feedback Policy',
        'type' => 'url',
        'comment' => 'For a NewsMediaOrganization or other news-related Organization, a statement about public engagement activities (for news media, the newsroom’s), including involving the public - digitally or otherwise -- in coverage decisions, reporting and activities after publication.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'address' => 
      array (
        'label' => 'Address',
        'type' => 'text',
        'comment' => 'Physical address of the item.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'areaServed' => 
      array (
        'label' => 'Area Served',
        'type' => 'text',
        'comment' => 'The geographic area where a service or offered item is provided.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'branchCode' => 
      array (
        'label' => 'Branch Code',
        'type' => 'text',
        'comment' => 'A short textual code (also called "store code") that uniquely identifies a place of business. The code is typically assigned by the parentOrganization and used in structured URLs.\\n\\nFor example, in the URL http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code "3047" is a branchCode for a particular branch.',
      ),
      'correctionsPolicy' => 
      array (
        'label' => 'Corrections Policy',
        'type' => 'url',
        'comment' => 'For an Organization (e.g. NewsMediaOrganization), a statement describing (in news media, the newsroom’s) disclosure and correction policy for errors.',
      ),
      'currenciesAccepted' => 
      array (
        'label' => 'Currencies Accepted',
        'type' => 'text',
        'comment' => 'The currency accepted.\\n\\nUse standard formats: [ISO 4217 currency format](http://en.wikipedia.org/wiki/ISO_4217), e.g. "USD"; [Ticker symbol](https://en.wikipedia.org/wiki/List_of_cryptocurrencies) for cryptocurrencies, e.g. "BTC"; well known names for [Local Exchange Trading Systems](https://en.wikipedia.org/wiki/Local_exchange_trading_system) (LETS) and other currency types, e.g. "Ithaca HOUR".',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'dissolutionDate' => 
      array (
        'label' => 'Dissolution Date',
        'type' => 'date',
        'comment' => 'The date that this organization was dissolved.',
      ),
      'diversityPolicy' => 
      array (
        'label' => 'Diversity Policy',
        'type' => 'url',
        'comment' => 'Statement on diversity policy by an Organization e.g. a NewsMediaOrganization. For a NewsMediaOrganization, a statement describing the newsroom’s diversity policy on both staffing and sources, typically providing staffing data.',
      ),
      'diversityStaffingReport' => 
      array (
        'label' => 'Diversity Staffing Report',
        'type' => 'url',
        'comment' => 'For an Organization (often but not necessarily a NewsMediaOrganization), a report on staffing diversity issues. In a news context this might be for example ASNE or RTDNA (US) reports, or self-reported.',
      ),
      'duns' => 
      array (
        'label' => 'Duns',
        'type' => 'text',
        'comment' => 'The Dun & Bradstreet DUNS number for identifying an organization or business person.',
      ),
      'email' => 
      array (
        'label' => 'Email',
        'type' => 'text',
        'comment' => 'Email address.',
      ),
      'ethicsPolicy' => 
      array (
        'label' => 'Ethics Policy',
        'type' => 'url',
        'comment' => 'Statement about ethics policy, e.g. of a NewsMediaOrganization regarding journalistic and publishing practices, or of a Restaurant, a page describing food source policies. In the case of a NewsMediaOrganization, an ethicsPolicy is typically a statement describing the personal, organizational, and corporate standards of behavior expected by the organization.',
      ),
      'faxNumber' => 
      array (
        'label' => 'Fax Number',
        'type' => 'text',
        'comment' => 'The fax number.',
      ),
      'floorLevel' => 
      array (
        'label' => 'Floor Level',
        'type' => 'text',
        'comment' => 'The floor level for an Accommodation in a multi-storey building. Since counting systems [vary internationally](https://en.wikipedia.org/wiki/Storey#Consecutive_number_floor_designations), the local system should be used where possible.',
      ),
      'foundingDate' => 
      array (
        'label' => 'Founding Date',
        'type' => 'date',
        'comment' => 'The date that this organization was founded.',
      ),
      'globalLocationNumber' => 
      array (
        'label' => 'Global Location Number',
        'type' => 'text',
        'comment' => 'The [Global Location Number](http://www.gs1.org/gln) (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.',
      ),
      'hasDriveThroughService' => 
      array (
        'label' => 'Has Drive Through Service',
        'type' => 'boolean',
        'comment' => 'Indicates whether some facility (e.g. FoodEstablishment, CovidTestingFacility) offers a service that can be used by driving through in a car. In the case of CovidTestingFacility such facilities could potentially help with social distancing from other potentially-infected users.',
      ),
      'hasGS1DigitalLink' => 
      array (
        'label' => 'Has G S1 Digital Link',
        'type' => 'url',
        'comment' => 'The GS1 digital link associated with the object. This URL should conform to the particular requirements of digital links. The link should only contain the Application Identifiers (AIs) that are relevant for the entity being annotated, for instance a Product or an Organization, and for the correct granularity. In particular, for products:A Digital Link that contains a serial number (AI 21) should only be present on instances of IndividualProductA Digital Link that contains a lot number (AI 10) should be annotated as SomeProducts if only products from that lot are sold, or IndividualProduct if there is only a specific product.A Digital Link that contains a global model number (AI 8013) should be attached to a Product or a ProductModel. Other item types should be adapted similarly.',
      ),
      'hasMap' => 
      array (
        'label' => 'Has Map',
        'type' => 'url',
        'comment' => 'A URL to a map of the place.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'isAccessibleForFree' => 
      array (
        'label' => 'Is Accessible For Free',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the item, event, or place is accessible for free.',
      ),
      'isicV4' => 
      array (
        'label' => 'Isic V4',
        'type' => 'text',
        'comment' => 'The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.',
      ),
      'iso6523Code' => 
      array (
        'label' => 'Iso6523 Code',
        'type' => 'text',
        'comment' => 'An organization identifier as defined in [ISO 6523(-1)](https://en.wikipedia.org/wiki/ISO/IEC_6523). The identifier should be in the `XXXX:YYYYYY:ZZZ` or `XXXX:YYYYYY`format. Where `XXXX` is a 4 digit _ICD_ (International Code Designator), `YYYYYY` is an _OID_ (Organization Identifier) with all formatting characters (dots, dashes, spaces) removed with a maximal length of 35 characters, and `ZZZ` is an optional OPI (Organization Part Identifier) with a maximum length of 35 characters. The various components (ICD, OID, OPI) are joined with a colon character (ASCII `0x3a`). Note that many existing organization identifiers defined as attributes like [leiCode](https://schema.org/leiCode) (`0199`), [duns](https://schema.org/duns) (`0060`) or [GLN](https://schema.org/globalLocationNumber) (`0088`) can be expressed using ISO-6523. If possible, ISO-6523 codes should be preferred to populating [vatID](https://schema.org/vatID) or [taxID](https://schema.org/taxID), as ISO identifiers are less ambiguous.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'knowsAbout' => 
      array (
        'label' => 'Knows About',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a topic that is known about - suggesting possible expertise but not implying it. We do not distinguish skill levels here, or relate this to educational content, events, objectives or JobPosting descriptions.',
      ),
      'knowsLanguage' => 
      array (
        'label' => 'Knows Language',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a known language. We do not distinguish skill levels or reading/writing/speaking/signing here. Use language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47).',
      ),
      'latitude' => 
      array (
        'label' => 'Latitude',
        'type' => 'number',
        'comment' => 'The latitude of a location. For example ```37.42242``` ([WGS 84](https://en.wikipedia.org/wiki/World_Geodetic_System)).',
      ),
      'legalName' => 
      array (
        'label' => 'Legal Name',
        'type' => 'text',
        'comment' => 'The official name of the organization, e.g. the registered company name.',
      ),
      'leiCode' => 
      array (
        'label' => 'Lei Code',
        'type' => 'text',
        'comment' => 'An organization identifier that uniquely identifies a legal entity as defined in ISO 17442.',
      ),
      'location' => 
      array (
        'label' => 'Location',
        'type' => 'text',
        'comment' => 'The location of, for example, where an event is happening, where an organization is located, or where an action takes place.',
      ),
      'logo' => 
      array (
        'label' => 'Logo',
        'type' => 'url',
        'comment' => 'An associated logo.',
      ),
      'longitude' => 
      array (
        'label' => 'Longitude',
        'type' => 'number',
        'comment' => 'The longitude of a location. For example ```-122.08585``` ([WGS 84](https://en.wikipedia.org/wiki/World_Geodetic_System)).',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'map' => 
      array (
        'label' => 'Map',
        'type' => 'url',
        'comment' => 'A URL to a map of the place.',
      ),
      'maps' => 
      array (
        'label' => 'Maps',
        'type' => 'url',
        'comment' => 'A URL to a map of the place.',
      ),
      'maximumAttendeeCapacity' => 
      array (
        'label' => 'Maximum Attendee Capacity',
        'type' => 'number',
        'comment' => 'The total number of individuals that may attend an event or venue.',
      ),
      'naics' => 
      array (
        'label' => 'Naics',
        'type' => 'text',
        'comment' => 'The North American Industry Classification System (NAICS) code for a particular organization or business person.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'openingHours' => 
      array (
        'label' => 'Opening Hours',
        'type' => 'text',
        'comment' => 'The general opening hours for a business. Opening hours can be specified as a weekly time range, starting with days, then times per day. Multiple days can be listed with commas \',\' separating each day. Day or time ranges are specified using a hyphen \'-\'.\\n\\n* Days are specified using the following two-letter combinations: ```Mo```, ```Tu```, ```We```, ```Th```, ```Fr```, ```Sa```, ```Su```.\\n* Times are specified using 24:00 format. For example, 3pm is specified as ```15:00```, 10am as ```10:00```. \\n* Here is an example: &lt;time itemprop="openingHours" datetime=&quot;Tu,Th 16:00-20:00&quot;&gt;Tuesdays and Thursdays 4-8pm&lt;/time&gt;.\\n* If a business is open 7 days a week, then it can be specified as &lt;time itemprop=&quot;openingHours&quot; datetime=&quot;Mo-Su&quot;&gt;Monday through Sunday, all day&lt;/time&gt;.',
      ),
      'ownershipFundingInfo' => 
      array (
        'label' => 'Ownership Funding Info',
        'type' => 'text',
        'comment' => 'For an Organization (often but not necessarily a NewsMediaOrganization), a description of organizational ownership structure; funding and grants. In a news/media setting, this is with particular reference to editorial independence. Note that the funder is also available and can be used to make basic funder information machine-readable.',
      ),
      'paymentAccepted' => 
      array (
        'label' => 'Payment Accepted',
        'type' => 'text',
        'comment' => 'Cash, Credit Card, Cryptocurrency, Local Exchange Tradings System, etc.',
      ),
      'priceRange' => 
      array (
        'label' => 'Price Range',
        'type' => 'text',
        'comment' => 'The price range of the business, for example ```$$$```.',
      ),
      'publicAccess' => 
      array (
        'label' => 'Public Access',
        'type' => 'boolean',
        'comment' => 'A flag to signal that the Place is open to public visitors. If this property is omitted there is no assumed default boolean value.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'skills' => 
      array (
        'label' => 'Skills',
        'type' => 'text',
        'comment' => 'A statement of knowledge, skill, ability, task or any other assertion expressing a competency that is either claimed by a person, an organization or desired or required to fulfill a role or to work in an occupation.',
      ),
      'slogan' => 
      array (
        'label' => 'Slogan',
        'type' => 'text',
        'comment' => 'A slogan or motto associated with the item.',
      ),
      'smokingAllowed' => 
      array (
        'label' => 'Smoking Allowed',
        'type' => 'boolean',
        'comment' => 'Indicates whether it is allowed to smoke in the place, e.g. in the restaurant, hotel or hotel room.',
      ),
      'taxID' => 
      array (
        'label' => 'Tax I D',
        'type' => 'text',
        'comment' => 'The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.',
      ),
      'telephone' => 
      array (
        'label' => 'Telephone',
        'type' => 'text',
        'comment' => 'The telephone number.',
      ),
      'tourBookingPage' => 
      array (
        'label' => 'Tour Booking Page',
        'type' => 'url',
        'comment' => 'A page providing information on how to book a tour of some Place, such as an Accommodation or ApartmentComplex in a real estate setting, as well as other kinds of tours as appropriate.',
      ),
      'unnamedSourcesPolicy' => 
      array (
        'label' => 'Unnamed Sources Policy',
        'type' => 'url',
        'comment' => 'For an Organization (typically a NewsMediaOrganization), a statement about policy on use of unnamed sources and the decision process required.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'vatID' => 
      array (
        'label' => 'Vat I D',
        'type' => 'text',
        'comment' => 'The value-added Tax ID of the organization or person with national prefix (for example IT123456789). Can also be described as iso6523Code with proper prefix.',
      ),
    ),
    'Product' => 
    array (
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'asin' => 
      array (
        'label' => 'Asin',
        'type' => 'text',
        'comment' => 'An Amazon Standard Identification Number (ASIN) is a 10-character alphanumeric unique identifier assigned by Amazon.com and its partners for product identification within the Amazon organization (summary from [Wikipedia](https://en.wikipedia.org/wiki/Amazon_Standard_Identification_Number)\'s article). Note also that this is a definition for how to include ASINs in Schema.org data, and not a definition of ASINs in general - see documentation from Amazon for authoritative details. ASINs are most commonly encoded as text strings, but the [asin] property supports URL/URI as potential values too.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'category' => 
      array (
        'label' => 'Category',
        'type' => 'text',
        'comment' => 'A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.',
      ),
      'color' => 
      array (
        'label' => 'Color',
        'type' => 'text',
        'comment' => 'The color of the product.',
      ),
      'colorSwatch' => 
      array (
        'label' => 'Color Swatch',
        'type' => 'url',
        'comment' => 'A color swatch image, visualizing the color of a Product. Should match the textual description specified in the color property. This can be a URL or a fully described ImageObject.',
      ),
      'countryOfAssembly' => 
      array (
        'label' => 'Country Of Assembly',
        'type' => 'text',
        'comment' => 'The place where the product was assembled.',
      ),
      'countryOfLastProcessing' => 
      array (
        'label' => 'Country Of Last Processing',
        'type' => 'text',
        'comment' => 'The place where the item (typically Product) was last processed and tested before importation.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'gtin' => 
      array (
        'label' => 'Gtin',
        'type' => 'text',
        'comment' => 'A Global Trade Item Number ([GTIN](https://www.gs1.org/standards/id-keys/gtin)). GTINs identify trade items, including products and services, using numeric identification codes. A correct gtin value should be a valid GTIN, which means that it should be an all-numeric string of either 8, 12, 13 or 14 digits, or a "GS1 Digital Link" URL based on such a string. The numeric component should also have a [valid GS1 check digit](https://www.gs1.org/services/check-digit-calculator) and meet the other rules for valid GTINs. See also [GS1\'s GTIN Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) and [Wikipedia](https://en.wikipedia.org/wiki/Global_Trade_Item_Number) for more details. Left-padding of the gtin values is not required or encouraged. The gtin property generalizes the earlier gtin8, gtin12, gtin13, and gtin14 properties. The GS1 [digital link specifications](https://www.gs1.org/standards/Digital-Link/) expresses GTINs as URLs (URIs, IRIs, etc.). Digital Links should be populated into the hasGS1DigitalLink attribute. Note also that this is a definition for how to include GTINs in Schema.org data, and not a definition of GTINs in general - see the GS1 documentation for authoritative details.',
      ),
      'gtin12' => 
      array (
        'label' => 'Gtin12',
        'type' => 'text',
        'comment' => 'The GTIN-12 code of the product, or the product to which the offer refers. The GTIN-12 is the 12-digit GS1 Identification Key composed of a U.P.C. Company Prefix, Item Reference, and Check Digit used to identify trade items. See [GS1 GTIN Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more details.',
      ),
      'gtin13' => 
      array (
        'label' => 'Gtin13',
        'type' => 'text',
        'comment' => 'The GTIN-13 code of the product, or the product to which the offer refers. This is equivalent to 13-digit ISBN codes and EAN UCC-13. Former 12-digit UPC codes can be converted into a GTIN-13 code by simply adding a preceding zero. See [GS1 GTIN Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more details.',
      ),
      'gtin14' => 
      array (
        'label' => 'Gtin14',
        'type' => 'text',
        'comment' => 'The GTIN-14 code of the product, or the product to which the offer refers. See [GS1 GTIN Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more details.',
      ),
      'gtin8' => 
      array (
        'label' => 'Gtin8',
        'type' => 'text',
        'comment' => 'The GTIN-8 code of the product, or the product to which the offer refers. This code is also known as EAN/UCC-8 or 8-digit EAN. See [GS1 GTIN Summary](http://www.gs1.org/barcodes/technical/idkeys/gtin) for more details.',
      ),
      'hasAdultConsideration' => 
      array (
        'label' => 'Has Adult Consideration',
        'type' => 'enum',
        'comment' => 'Used to tag an item to be intended or suitable for consumption or use by adults only.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/AlcoholConsideration',
            'label' => 'AlcoholConsideration',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/DangerousGoodConsideration',
            'label' => 'DangerousGoodConsideration',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/HealthcareConsideration',
            'label' => 'HealthcareConsideration',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/NarcoticConsideration',
            'label' => 'NarcoticConsideration',
          ),
          4 => 
          array (
            'value' => 'https://schema.org/ReducedRelevanceForChildrenConsideration',
            'label' => 'ReducedRelevanceForChildrenConsideration',
          ),
          5 => 
          array (
            'value' => 'https://schema.org/SexualContentConsideration',
            'label' => 'SexualContentConsideration',
          ),
          6 => 
          array (
            'value' => 'https://schema.org/TobaccoNicotineConsideration',
            'label' => 'TobaccoNicotineConsideration',
          ),
          7 => 
          array (
            'value' => 'https://schema.org/UnclassifiedAdultConsideration',
            'label' => 'UnclassifiedAdultConsideration',
          ),
          8 => 
          array (
            'value' => 'https://schema.org/ViolenceConsideration',
            'label' => 'ViolenceConsideration',
          ),
          9 => 
          array (
            'value' => 'https://schema.org/WeaponConsideration',
            'label' => 'WeaponConsideration',
          ),
        ),
      ),
      'hasGS1DigitalLink' => 
      array (
        'label' => 'Has G S1 Digital Link',
        'type' => 'url',
        'comment' => 'The GS1 digital link associated with the object. This URL should conform to the particular requirements of digital links. The link should only contain the Application Identifiers (AIs) that are relevant for the entity being annotated, for instance a Product or an Organization, and for the correct granularity. In particular, for products:A Digital Link that contains a serial number (AI 21) should only be present on instances of IndividualProductA Digital Link that contains a lot number (AI 10) should be annotated as SomeProducts if only products from that lot are sold, or IndividualProduct if there is only a specific product.A Digital Link that contains a global model number (AI 8013) should be attached to a Product or a ProductModel. Other item types should be adapted similarly.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'inProductGroupWithID' => 
      array (
        'label' => 'In Product Group With I D',
        'type' => 'text',
        'comment' => 'Indicates the productGroupID for a ProductGroup that this product isVariantOf.',
      ),
      'isFamilyFriendly' => 
      array (
        'label' => 'Is Family Friendly',
        'type' => 'boolean',
        'comment' => 'Indicates whether this content is family friendly.',
      ),
      'itemCondition' => 
      array (
        'label' => 'Item Condition',
        'type' => 'enum',
        'comment' => 'A predefined value from OfferItemCondition specifying the condition of the product or service, or the products or services included in the offer. Also used for product return policies to specify the condition of products accepted for returns.',
        'enum' => 
        array (
          0 => 
          array (
            'value' => 'https://schema.org/DamagedCondition',
            'label' => 'DamagedCondition',
          ),
          1 => 
          array (
            'value' => 'https://schema.org/NewCondition',
            'label' => 'NewCondition',
          ),
          2 => 
          array (
            'value' => 'https://schema.org/RefurbishedCondition',
            'label' => 'RefurbishedCondition',
          ),
          3 => 
          array (
            'value' => 'https://schema.org/UsedCondition',
            'label' => 'UsedCondition',
          ),
        ),
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'logo' => 
      array (
        'label' => 'Logo',
        'type' => 'url',
        'comment' => 'An associated logo.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'material' => 
      array (
        'label' => 'Material',
        'type' => 'text',
        'comment' => 'A material that something is made from, e.g. leather, wool, cotton, paper.',
      ),
      'mobileUrl' => 
      array (
        'label' => 'Mobile Url',
        'type' => 'text',
        'comment' => 'The mobileUrl property is provided for specific situations in which data consumers need to determine whether one of several provided URLs is a dedicated \'mobile site\'. To discourage over-use, and reflecting intial usecases, the property is expected only on Product and Offer, rather than Thing. The general trend in web technology is towards [responsive design](https://en.wikipedia.org/wiki/Responsive_web_design) in which content can be flexibly adapted to a wide range of browsing environments. Pages and sites referenced with the long-established url property should ideally also be usable on a wide variety of devices, including mobile phones. In most cases, it would be pointless and counter productive to attempt to update all url markup to use mobileUrl for more mobile-oriented pages. The property is intended for the case when items (primarily Product and Offer) have extra URLs hosted on an additional "mobile site" alongside the main one. It should not be taken as an endorsement of this publication style.',
      ),
      'model' => 
      array (
        'label' => 'Model',
        'type' => 'text',
        'comment' => 'The model of the product. Use with the URL of a ProductModel or a textual representation of the model identifier. The URL of the ProductModel can be from an external source. It is recommended to additionally provide strong product identifiers via the gtin8/gtin13/gtin14 and mpn properties.',
      ),
      'mpn' => 
      array (
        'label' => 'Mpn',
        'type' => 'text',
        'comment' => 'The Manufacturer Part Number (MPN) of the product, or the product to which the offer refers.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'negativeNotes' => 
      array (
        'label' => 'Negative Notes',
        'type' => 'text',
        'comment' => 'Provides negative considerations regarding something, most typically in pro/con lists for reviews (alongside positiveNotes). For symmetry In the case of a Review, the property describes the itemReviewed from the perspective of the review; in the case of a Product, the product itself is being described. Since product descriptions tend to emphasise positive claims, it may be relatively unusual to find negativeNotes used in this way. Nevertheless for the sake of symmetry, negativeNotes can be used on Product. The property values can be expressed either as unstructured text (repeated as necessary), or if ordered, as a list (in which case the most negative is at the beginning of the list).',
      ),
      'nsn' => 
      array (
        'label' => 'Nsn',
        'type' => 'text',
        'comment' => 'Indicates the [NATO stock number](https://en.wikipedia.org/wiki/NATO_Stock_Number) (nsn) of a Product.',
      ),
      'pattern' => 
      array (
        'label' => 'Pattern',
        'type' => 'text',
        'comment' => 'A pattern that something has, for example \'polka dot\', \'striped\', \'Canadian flag\'. Values are typically expressed as text, although links to controlled value schemes are also supported.',
      ),
      'positiveNotes' => 
      array (
        'label' => 'Positive Notes',
        'type' => 'text',
        'comment' => 'Provides positive considerations regarding something, for example product highlights or (alongside negativeNotes) pro/con lists for reviews. In the case of a Review, the property describes the itemReviewed from the perspective of the review; in the case of a Product, the product itself is being described. The property values can be expressed either as unstructured text (repeated as necessary), or if ordered, as a list (in which case the most positive is at the beginning of the list).',
      ),
      'productID' => 
      array (
        'label' => 'Product I D',
        'type' => 'text',
        'comment' => 'The product identifier, such as ISBN. For example: ``` meta itemprop="productID" content="isbn:123-456-789" ```.',
      ),
      'productionDate' => 
      array (
        'label' => 'Production Date',
        'type' => 'date',
        'comment' => 'The date of production of the item, e.g. vehicle.',
      ),
      'purchaseDate' => 
      array (
        'label' => 'Purchase Date',
        'type' => 'date',
        'comment' => 'The date the item, e.g. vehicle, was purchased by the current owner.',
      ),
      'releaseDate' => 
      array (
        'label' => 'Release Date',
        'type' => 'date',
        'comment' => 'The release date of a product or product model. This can be used to distinguish the exact variant of a product.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'size' => 
      array (
        'label' => 'Size',
        'type' => 'text',
        'comment' => 'A standardized size of a product or creative work, specified either through a simple textual string (for example \'XL\', \'32Wx34L\'), a QuantitativeValue with a unitCode, or a comprehensive and structured SizeSpecification; in other cases, the width, height, depth and weight properties may be more applicable.',
      ),
      'sku' => 
      array (
        'label' => 'Sku',
        'type' => 'text',
        'comment' => 'The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a product or service, or the product to which the offer refers.',
      ),
      'slogan' => 
      array (
        'label' => 'Slogan',
        'type' => 'text',
        'comment' => 'A slogan or motto associated with the item.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
    ),
    'Service' => 
    array (
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'areaServed' => 
      array (
        'label' => 'Area Served',
        'type' => 'text',
        'comment' => 'The geographic area where a service or offered item is provided.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'category' => 
      array (
        'label' => 'Category',
        'type' => 'text',
        'comment' => 'A category for the item. Greater signs or slashes can be used to informally indicate a category hierarchy.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'logo' => 
      array (
        'label' => 'Logo',
        'type' => 'url',
        'comment' => 'An associated logo.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'providerMobility' => 
      array (
        'label' => 'Provider Mobility',
        'type' => 'text',
        'comment' => 'Indicates the mobility of a provided service (e.g. \'static\', \'dynamic\').',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'serviceType' => 
      array (
        'label' => 'Service Type',
        'type' => 'text',
        'comment' => 'The type of service being offered, e.g. veterans\' benefits, emergency relief, etc.',
      ),
      'slogan' => 
      array (
        'label' => 'Slogan',
        'type' => 'text',
        'comment' => 'A slogan or motto associated with the item.',
      ),
      'termsOfService' => 
      array (
        'label' => 'Terms Of Service',
        'type' => 'text',
        'comment' => 'Human-readable terms of service documentation.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
    ),
    'Person' => 
    array (
      'additionalName' => 
      array (
        'label' => 'Additional Name',
        'type' => 'text',
        'comment' => 'An additional name for a Person, can be used for a middle name.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'address' => 
      array (
        'label' => 'Address',
        'type' => 'text',
        'comment' => 'Physical address of the item.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'birthDate' => 
      array (
        'label' => 'Birth Date',
        'type' => 'date',
        'comment' => 'Date of birth.',
      ),
      'callSign' => 
      array (
        'label' => 'Call Sign',
        'type' => 'text',
        'comment' => 'A [callsign](https://en.wikipedia.org/wiki/Call_sign), as used in broadcasting and radio communications to identify people, radio and TV stations, or vehicles.',
      ),
      'colleague' => 
      array (
        'label' => 'Colleague',
        'type' => 'url',
        'comment' => 'A colleague of the person.',
      ),
      'deathDate' => 
      array (
        'label' => 'Death Date',
        'type' => 'date',
        'comment' => 'Date of death.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'duns' => 
      array (
        'label' => 'Duns',
        'type' => 'text',
        'comment' => 'The Dun & Bradstreet DUNS number for identifying an organization or business person.',
      ),
      'email' => 
      array (
        'label' => 'Email',
        'type' => 'text',
        'comment' => 'Email address.',
      ),
      'familyName' => 
      array (
        'label' => 'Family Name',
        'type' => 'text',
        'comment' => 'Family name. In the U.S., the last name of a Person.',
      ),
      'faxNumber' => 
      array (
        'label' => 'Fax Number',
        'type' => 'text',
        'comment' => 'The fax number.',
      ),
      'gender' => 
      array (
        'label' => 'Gender',
        'type' => 'text',
        'comment' => 'Gender of something, typically a Person, but possibly also fictional characters, animals, etc. While https://schema.org/Male and https://schema.org/Female may be used, text strings are also acceptable for people who are not a binary gender. The gender property can also be used in an extended sense to cover e.g. the gender of sports teams. As with the gender of individuals, we do not try to enumerate all possibilities. A mixed-gender SportsTeam can be indicated with a text value of "Mixed".',
      ),
      'givenName' => 
      array (
        'label' => 'Given Name',
        'type' => 'text',
        'comment' => 'Given name. In the U.S., the first name of a Person.',
      ),
      'globalLocationNumber' => 
      array (
        'label' => 'Global Location Number',
        'type' => 'text',
        'comment' => 'The [Global Location Number](http://www.gs1.org/gln) (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.',
      ),
      'honorificPrefix' => 
      array (
        'label' => 'Honorific Prefix',
        'type' => 'text',
        'comment' => 'An honorific prefix preceding a Person\'s name such as Dr/Mrs/Mr.',
      ),
      'honorificSuffix' => 
      array (
        'label' => 'Honorific Suffix',
        'type' => 'text',
        'comment' => 'An honorific suffix following a Person\'s name such as M.D./PhD/MSCSW.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'isicV4' => 
      array (
        'label' => 'Isic V4',
        'type' => 'text',
        'comment' => 'The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.',
      ),
      'jobTitle' => 
      array (
        'label' => 'Job Title',
        'type' => 'text',
        'comment' => 'The job title of the person (for example, Financial Manager).',
      ),
      'knowsAbout' => 
      array (
        'label' => 'Knows About',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a topic that is known about - suggesting possible expertise but not implying it. We do not distinguish skill levels here, or relate this to educational content, events, objectives or JobPosting descriptions.',
      ),
      'knowsLanguage' => 
      array (
        'label' => 'Knows Language',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a known language. We do not distinguish skill levels or reading/writing/speaking/signing here. Use language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47).',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'naics' => 
      array (
        'label' => 'Naics',
        'type' => 'text',
        'comment' => 'The North American Industry Classification System (NAICS) code for a particular organization or business person.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'pronouns' => 
      array (
        'label' => 'Pronouns',
        'type' => 'text',
        'comment' => 'A short string listing or describing pronouns for a person. Typically the person concerned is the best authority as pronouns are a critical part of personal identity and expression. Publishers and consumers of this information are reminded to treat this data responsibly, take country-specific laws related to gender expression into account, and be wary of out-of-date data and drawing unwarranted inferences about the person being described. In English, formulations such as "they/them", "she/her", and "he/him" are commonly used online and can also be used here. We do not intend to enumerate all possible micro-syntaxes in all languages. More structured and well-defined external values for pronouns can be referenced using the StructuredValue or DefinedTerm values.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'skills' => 
      array (
        'label' => 'Skills',
        'type' => 'text',
        'comment' => 'A statement of knowledge, skill, ability, task or any other assertion expressing a competency that is either claimed by a person, an organization or desired or required to fulfill a role or to work in an occupation.',
      ),
      'taxID' => 
      array (
        'label' => 'Tax I D',
        'type' => 'text',
        'comment' => 'The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.',
      ),
      'telephone' => 
      array (
        'label' => 'Telephone',
        'type' => 'text',
        'comment' => 'The telephone number.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'vatID' => 
      array (
        'label' => 'Vat I D',
        'type' => 'text',
        'comment' => 'The value-added Tax ID of the organization or person with national prefix (for example IT123456789). Can also be described as iso6523Code with proper prefix.',
      ),
    ),
    'Organization' => 
    array (
      'acceptedPaymentMethod' => 
      array (
        'label' => 'Accepted Payment Method',
        'type' => 'text',
        'comment' => 'The payment method(s) that are accepted in general by an organization, or for some specific demand or offer.',
      ),
      'actionableFeedbackPolicy' => 
      array (
        'label' => 'Actionable Feedback Policy',
        'type' => 'url',
        'comment' => 'For a NewsMediaOrganization or other news-related Organization, a statement about public engagement activities (for news media, the newsroom’s), including involving the public - digitally or otherwise -- in coverage decisions, reporting and activities after publication.',
      ),
      'additionalType' => 
      array (
        'label' => 'Additional Type',
        'type' => 'text',
        'comment' => 'An additional type for the item, typically used for adding more specific types from external vocabularies in microdata syntax. This is a relationship between something and a class that the thing is in. Typically the value is a URI-identified RDF class, and in this case corresponds to the use of rdf:type in RDF. Text values can be used sparingly, for cases where useful information can be added without their being an appropriate schema to reference. In the case of text values, the class label should follow the schema.org style guide.',
      ),
      'address' => 
      array (
        'label' => 'Address',
        'type' => 'text',
        'comment' => 'Physical address of the item.',
      ),
      'alternateName' => 
      array (
        'label' => 'Alternate Name',
        'type' => 'text',
        'comment' => 'An alias for the item.',
      ),
      'areaServed' => 
      array (
        'label' => 'Area Served',
        'type' => 'text',
        'comment' => 'The geographic area where a service or offered item is provided.',
      ),
      'award' => 
      array (
        'label' => 'Award',
        'type' => 'text',
        'comment' => 'An award won by or for this item.',
      ),
      'awards' => 
      array (
        'label' => 'Awards',
        'type' => 'text',
        'comment' => 'Awards won by or for this item.',
      ),
      'correctionsPolicy' => 
      array (
        'label' => 'Corrections Policy',
        'type' => 'url',
        'comment' => 'For an Organization (e.g. NewsMediaOrganization), a statement describing (in news media, the newsroom’s) disclosure and correction policy for errors.',
      ),
      'description' => 
      array (
        'label' => 'Description',
        'type' => 'text',
        'comment' => 'A description of the item.',
      ),
      'disambiguatingDescription' => 
      array (
        'label' => 'Disambiguating Description',
        'type' => 'text',
        'comment' => 'A sub property of description. A short description of the item used to disambiguate from other, similar items. Information from other properties (in particular, name) may be necessary for the description to be useful for disambiguation.',
      ),
      'dissolutionDate' => 
      array (
        'label' => 'Dissolution Date',
        'type' => 'date',
        'comment' => 'The date that this organization was dissolved.',
      ),
      'diversityPolicy' => 
      array (
        'label' => 'Diversity Policy',
        'type' => 'url',
        'comment' => 'Statement on diversity policy by an Organization e.g. a NewsMediaOrganization. For a NewsMediaOrganization, a statement describing the newsroom’s diversity policy on both staffing and sources, typically providing staffing data.',
      ),
      'diversityStaffingReport' => 
      array (
        'label' => 'Diversity Staffing Report',
        'type' => 'url',
        'comment' => 'For an Organization (often but not necessarily a NewsMediaOrganization), a report on staffing diversity issues. In a news context this might be for example ASNE or RTDNA (US) reports, or self-reported.',
      ),
      'duns' => 
      array (
        'label' => 'Duns',
        'type' => 'text',
        'comment' => 'The Dun & Bradstreet DUNS number for identifying an organization or business person.',
      ),
      'email' => 
      array (
        'label' => 'Email',
        'type' => 'text',
        'comment' => 'Email address.',
      ),
      'ethicsPolicy' => 
      array (
        'label' => 'Ethics Policy',
        'type' => 'url',
        'comment' => 'Statement about ethics policy, e.g. of a NewsMediaOrganization regarding journalistic and publishing practices, or of a Restaurant, a page describing food source policies. In the case of a NewsMediaOrganization, an ethicsPolicy is typically a statement describing the personal, organizational, and corporate standards of behavior expected by the organization.',
      ),
      'faxNumber' => 
      array (
        'label' => 'Fax Number',
        'type' => 'text',
        'comment' => 'The fax number.',
      ),
      'foundingDate' => 
      array (
        'label' => 'Founding Date',
        'type' => 'date',
        'comment' => 'The date that this organization was founded.',
      ),
      'globalLocationNumber' => 
      array (
        'label' => 'Global Location Number',
        'type' => 'text',
        'comment' => 'The [Global Location Number](http://www.gs1.org/gln) (GLN, sometimes also referred to as International Location Number or ILN) of the respective organization, person, or place. The GLN is a 13-digit number used to identify parties and physical locations.',
      ),
      'hasGS1DigitalLink' => 
      array (
        'label' => 'Has G S1 Digital Link',
        'type' => 'url',
        'comment' => 'The GS1 digital link associated with the object. This URL should conform to the particular requirements of digital links. The link should only contain the Application Identifiers (AIs) that are relevant for the entity being annotated, for instance a Product or an Organization, and for the correct granularity. In particular, for products:A Digital Link that contains a serial number (AI 21) should only be present on instances of IndividualProductA Digital Link that contains a lot number (AI 10) should be annotated as SomeProducts if only products from that lot are sold, or IndividualProduct if there is only a specific product.A Digital Link that contains a global model number (AI 8013) should be attached to a Product or a ProductModel. Other item types should be adapted similarly.',
      ),
      'identifier' => 
      array (
        'label' => 'Identifier',
        'type' => 'text',
        'comment' => 'The identifier property represents any kind of identifier for any kind of Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides dedicated properties for representing many of these, either as textual strings or as URL (URI) links. See [background notes](/docs/datamodel.html#identifierBg) for more details.',
      ),
      'image' => 
      array (
        'label' => 'Image',
        'type' => 'url',
        'comment' => 'An image of the item. This can be a URL or a fully described ImageObject.',
      ),
      'isicV4' => 
      array (
        'label' => 'Isic V4',
        'type' => 'text',
        'comment' => 'The International Standard of Industrial Classification of All Economic Activities (ISIC), Revision 4 code for a particular organization, business person, or place.',
      ),
      'iso6523Code' => 
      array (
        'label' => 'Iso6523 Code',
        'type' => 'text',
        'comment' => 'An organization identifier as defined in [ISO 6523(-1)](https://en.wikipedia.org/wiki/ISO/IEC_6523). The identifier should be in the `XXXX:YYYYYY:ZZZ` or `XXXX:YYYYYY`format. Where `XXXX` is a 4 digit _ICD_ (International Code Designator), `YYYYYY` is an _OID_ (Organization Identifier) with all formatting characters (dots, dashes, spaces) removed with a maximal length of 35 characters, and `ZZZ` is an optional OPI (Organization Part Identifier) with a maximum length of 35 characters. The various components (ICD, OID, OPI) are joined with a colon character (ASCII `0x3a`). Note that many existing organization identifiers defined as attributes like [leiCode](https://schema.org/leiCode) (`0199`), [duns](https://schema.org/duns) (`0060`) or [GLN](https://schema.org/globalLocationNumber) (`0088`) can be expressed using ISO-6523. If possible, ISO-6523 codes should be preferred to populating [vatID](https://schema.org/vatID) or [taxID](https://schema.org/taxID), as ISO identifiers are less ambiguous.',
      ),
      'keywords' => 
      array (
        'label' => 'Keywords',
        'type' => 'text',
        'comment' => 'Keywords or tags used to describe some item. Multiple textual entries in a keywords list are typically delimited by commas, or by repeating the property.',
      ),
      'knowsAbout' => 
      array (
        'label' => 'Knows About',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a topic that is known about - suggesting possible expertise but not implying it. We do not distinguish skill levels here, or relate this to educational content, events, objectives or JobPosting descriptions.',
      ),
      'knowsLanguage' => 
      array (
        'label' => 'Knows Language',
        'type' => 'text',
        'comment' => 'Of a Person, and less typically of an Organization, to indicate a known language. We do not distinguish skill levels or reading/writing/speaking/signing here. Use language codes from the [IETF BCP 47 standard](http://tools.ietf.org/html/bcp47).',
      ),
      'legalName' => 
      array (
        'label' => 'Legal Name',
        'type' => 'text',
        'comment' => 'The official name of the organization, e.g. the registered company name.',
      ),
      'leiCode' => 
      array (
        'label' => 'Lei Code',
        'type' => 'text',
        'comment' => 'An organization identifier that uniquely identifies a legal entity as defined in ISO 17442.',
      ),
      'location' => 
      array (
        'label' => 'Location',
        'type' => 'text',
        'comment' => 'The location of, for example, where an event is happening, where an organization is located, or where an action takes place.',
      ),
      'logo' => 
      array (
        'label' => 'Logo',
        'type' => 'url',
        'comment' => 'An associated logo.',
      ),
      'mainEntityOfPage' => 
      array (
        'label' => 'Main Entity Of Page',
        'type' => 'url',
        'comment' => 'Indicates a page (or other CreativeWork) for which this thing is the main entity being described. See [background notes](/docs/datamodel.html#mainEntityBackground) for details.',
      ),
      'naics' => 
      array (
        'label' => 'Naics',
        'type' => 'text',
        'comment' => 'The North American Industry Classification System (NAICS) code for a particular organization or business person.',
      ),
      'name' => 
      array (
        'label' => 'Name',
        'type' => 'text',
        'comment' => 'The name of the item.',
      ),
      'ownershipFundingInfo' => 
      array (
        'label' => 'Ownership Funding Info',
        'type' => 'text',
        'comment' => 'For an Organization (often but not necessarily a NewsMediaOrganization), a description of organizational ownership structure; funding and grants. In a news/media setting, this is with particular reference to editorial independence. Note that the funder is also available and can be used to make basic funder information machine-readable.',
      ),
      'publishingPrinciples' => 
      array (
        'label' => 'Publishing Principles',
        'type' => 'url',
        'comment' => 'The publishingPrinciples property indicates (typically via URL) a document describing the editorial principles of an Organization (or individual, e.g. a Person writing a blog) that relate to their activities as a publisher, e.g. ethics or diversity policies. When applied to a CreativeWork (e.g. NewsArticle) the principles are those of the party primarily responsible for the creation of the CreativeWork. While such policies are most typically expressed in natural language, sometimes related information (e.g. indicating a funder) can be expressed using schema.org terminology.',
      ),
      'sameAs' => 
      array (
        'label' => 'Same As',
        'type' => 'url',
        'comment' => 'URL of a reference Web page that unambiguously indicates the item\'s identity. E.g. the URL of the item\'s Wikipedia page, Wikidata entry, or official website.',
      ),
      'skills' => 
      array (
        'label' => 'Skills',
        'type' => 'text',
        'comment' => 'A statement of knowledge, skill, ability, task or any other assertion expressing a competency that is either claimed by a person, an organization or desired or required to fulfill a role or to work in an occupation.',
      ),
      'slogan' => 
      array (
        'label' => 'Slogan',
        'type' => 'text',
        'comment' => 'A slogan or motto associated with the item.',
      ),
      'taxID' => 
      array (
        'label' => 'Tax I D',
        'type' => 'text',
        'comment' => 'The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US or the CIF/NIF in Spain.',
      ),
      'telephone' => 
      array (
        'label' => 'Telephone',
        'type' => 'text',
        'comment' => 'The telephone number.',
      ),
      'unnamedSourcesPolicy' => 
      array (
        'label' => 'Unnamed Sources Policy',
        'type' => 'url',
        'comment' => 'For an Organization (typically a NewsMediaOrganization), a statement about policy on use of unnamed sources and the decision process required.',
      ),
      'url' => 
      array (
        'label' => 'Url',
        'type' => 'url',
        'comment' => 'URL of the item.',
      ),
      'vatID' => 
      array (
        'label' => 'Vat I D',
        'type' => 'text',
        'comment' => 'The value-added Tax ID of the organization or person with national prefix (for example IT123456789). Can also be described as iso6523Code with proper prefix.',
      ),
    ),
  ),
  'enums' => 
  array (
    'actionStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ActiveActionStatus',
        'label' => 'ActiveActionStatus',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/CompletedActionStatus',
        'label' => 'CompletedActionStatus',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/FailedActionStatus',
        'label' => 'FailedActionStatus',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/PotentialActionStatus',
        'label' => 'PotentialActionStatus',
      ),
    ),
    'albumProductionType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CompilationAlbum',
        'label' => 'CompilationAlbum',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/DJMixAlbum',
        'label' => 'DJMixAlbum',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/DemoAlbum',
        'label' => 'DemoAlbum',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/LiveAlbum',
        'label' => 'LiveAlbum',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/MixtapeAlbum',
        'label' => 'MixtapeAlbum',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/RemixAlbum',
        'label' => 'RemixAlbum',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/SoundtrackAlbum',
        'label' => 'SoundtrackAlbum',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/SpokenWordAlbum',
        'label' => 'SpokenWordAlbum',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/StudioAlbum',
        'label' => 'StudioAlbum',
      ),
    ),
    'albumReleaseType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AlbumRelease',
        'label' => 'AlbumRelease',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/BroadcastRelease',
        'label' => 'BroadcastRelease',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/EPRelease',
        'label' => 'EPRelease',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/SingleRelease',
        'label' => 'SingleRelease',
      ),
    ),
    'appliesToDeliveryMethod' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/LockerDelivery',
        'label' => 'LockerDelivery',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OnSitePickup',
        'label' => 'OnSitePickup',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ParcelService',
        'label' => 'ParcelService',
      ),
    ),
    'availability' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/BackOrder',
        'label' => 'BackOrder',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Discontinued',
        'label' => 'Discontinued',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/InStock',
        'label' => 'InStock',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/InStoreOnly',
        'label' => 'InStoreOnly',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/LimitedAvailability',
        'label' => 'LimitedAvailability',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/MadeToOrder',
        'label' => 'MadeToOrder',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/OnlineOnly',
        'label' => 'OnlineOnly',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/OutOfStock',
        'label' => 'OutOfStock',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/PreOrder',
        'label' => 'PreOrder',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/PreSale',
        'label' => 'PreSale',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/Reserved',
        'label' => 'Reserved',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/SoldOut',
        'label' => 'SoldOut',
      ),
    ),
    'availableDeliveryMethod' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/LockerDelivery',
        'label' => 'LockerDelivery',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OnSitePickup',
        'label' => 'OnSitePickup',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ParcelService',
        'label' => 'ParcelService',
      ),
    ),
    'boardingPolicy' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/GroupBoardingPolicy',
        'label' => 'GroupBoardingPolicy',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ZoneBoardingPolicy',
        'label' => 'ZoneBoardingPolicy',
      ),
    ),
    'bookFormat' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AudiobookFormat',
        'label' => 'AudiobookFormat',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EBook',
        'label' => 'EBook',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/GraphicNovel',
        'label' => 'GraphicNovel',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Hardcover',
        'label' => 'Hardcover',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Pamphlet',
        'label' => 'Pamphlet',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Paperback',
        'label' => 'Paperback',
      ),
    ),
    'businessDays' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Friday',
        'label' => 'Friday',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Monday',
        'label' => 'Monday',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/PublicHolidays',
        'label' => 'PublicHolidays',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Saturday',
        'label' => 'Saturday',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Sunday',
        'label' => 'Sunday',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Thursday',
        'label' => 'Thursday',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/Tuesday',
        'label' => 'Tuesday',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/Wednesday',
        'label' => 'Wednesday',
      ),
    ),
    'certificationStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CertificationActive',
        'label' => 'CertificationActive',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/CertificationInactive',
        'label' => 'CertificationInactive',
      ),
    ),
    'contactOption' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/HearingImpairedSupported',
        'label' => 'HearingImpairedSupported',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/TollFree',
        'label' => 'TollFree',
      ),
    ),
    'costCategory' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ReimbursementCap',
        'label' => 'ReimbursementCap',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Retail',
        'label' => 'Retail',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/Wholesale',
        'label' => 'Wholesale',
      ),
    ),
    'customerRemorseReturnFees' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/FreeReturn',
        'label' => 'FreeReturn',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OriginalShippingFees',
        'label' => 'OriginalShippingFees',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/RestockingFees',
        'label' => 'RestockingFees',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ReturnFeesCustomerResponsibility',
        'label' => 'ReturnFeesCustomerResponsibility',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/ReturnShippingFees',
        'label' => 'ReturnShippingFees',
      ),
    ),
    'customerRemorseReturnLabelSource' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ReturnLabelCustomerResponsibility',
        'label' => 'ReturnLabelCustomerResponsibility',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReturnLabelDownloadAndPrint',
        'label' => 'ReturnLabelDownloadAndPrint',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ReturnLabelInBox',
        'label' => 'ReturnLabelInBox',
      ),
    ),
    'dayOfWeek' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Friday',
        'label' => 'Friday',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Monday',
        'label' => 'Monday',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/PublicHolidays',
        'label' => 'PublicHolidays',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Saturday',
        'label' => 'Saturday',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Sunday',
        'label' => 'Sunday',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Thursday',
        'label' => 'Thursday',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/Tuesday',
        'label' => 'Tuesday',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/Wednesday',
        'label' => 'Wednesday',
      ),
    ),
    'deliveryMethod' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/LockerDelivery',
        'label' => 'LockerDelivery',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OnSitePickup',
        'label' => 'OnSitePickup',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ParcelService',
        'label' => 'ParcelService',
      ),
    ),
    'digitalSourceType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AlgorithmicMediaDigitalSource',
        'label' => 'AlgorithmicMediaDigitalSource',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/AlgorithmicallyEnhancedDigitalSource',
        'label' => 'AlgorithmicallyEnhancedDigitalSource',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CompositeCaptureDigitalSource',
        'label' => 'CompositeCaptureDigitalSource',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/CompositeDigitalSource',
        'label' => 'CompositeDigitalSource',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/CompositeSyntheticDigitalSource',
        'label' => 'CompositeSyntheticDigitalSource',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/CompositeWithTrainedAlgorithmicMediaDigitalSource',
        'label' => 'CompositeWithTrainedAlgorithmicMediaDigitalSource',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/DataDrivenMediaDigitalSource',
        'label' => 'DataDrivenMediaDigitalSource',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/DigitalArtDigitalSource',
        'label' => 'DigitalArtDigitalSource',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/DigitalCaptureDigitalSource',
        'label' => 'DigitalCaptureDigitalSource',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/MinorHumanEditsDigitalSource',
        'label' => 'MinorHumanEditsDigitalSource',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/MultiFrameComputationalCaptureDigitalSource',
        'label' => 'MultiFrameComputationalCaptureDigitalSource',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/NegativeFilmDigitalSource',
        'label' => 'NegativeFilmDigitalSource',
      ),
      12 => 
      array (
        'value' => 'https://schema.org/PositiveFilmDigitalSource',
        'label' => 'PositiveFilmDigitalSource',
      ),
      13 => 
      array (
        'value' => 'https://schema.org/PrintDigitalSource',
        'label' => 'PrintDigitalSource',
      ),
      14 => 
      array (
        'value' => 'https://schema.org/ScreenCaptureDigitalSource',
        'label' => 'ScreenCaptureDigitalSource',
      ),
      15 => 
      array (
        'value' => 'https://schema.org/TrainedAlgorithmicMediaDigitalSource',
        'label' => 'TrainedAlgorithmicMediaDigitalSource',
      ),
      16 => 
      array (
        'value' => 'https://schema.org/VirtualRecordingDigitalSource',
        'label' => 'VirtualRecordingDigitalSource',
      ),
    ),
    'energyEfficiencyScaleMax' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA',
        'label' => 'EUEnergyEfficiencyCategoryA',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA1Plus',
        'label' => 'EUEnergyEfficiencyCategoryA1Plus',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA2Plus',
        'label' => 'EUEnergyEfficiencyCategoryA2Plus',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA3Plus',
        'label' => 'EUEnergyEfficiencyCategoryA3Plus',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryB',
        'label' => 'EUEnergyEfficiencyCategoryB',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryC',
        'label' => 'EUEnergyEfficiencyCategoryC',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryD',
        'label' => 'EUEnergyEfficiencyCategoryD',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryE',
        'label' => 'EUEnergyEfficiencyCategoryE',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryF',
        'label' => 'EUEnergyEfficiencyCategoryF',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryG',
        'label' => 'EUEnergyEfficiencyCategoryG',
      ),
    ),
    'energyEfficiencyScaleMin' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA',
        'label' => 'EUEnergyEfficiencyCategoryA',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA1Plus',
        'label' => 'EUEnergyEfficiencyCategoryA1Plus',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA2Plus',
        'label' => 'EUEnergyEfficiencyCategoryA2Plus',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryA3Plus',
        'label' => 'EUEnergyEfficiencyCategoryA3Plus',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryB',
        'label' => 'EUEnergyEfficiencyCategoryB',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryC',
        'label' => 'EUEnergyEfficiencyCategoryC',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryD',
        'label' => 'EUEnergyEfficiencyCategoryD',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryE',
        'label' => 'EUEnergyEfficiencyCategoryE',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryF',
        'label' => 'EUEnergyEfficiencyCategoryF',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/EUEnergyEfficiencyCategoryG',
        'label' => 'EUEnergyEfficiencyCategoryG',
      ),
    ),
    'eventAttendanceMode' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/MixedEventAttendanceMode',
        'label' => 'MixedEventAttendanceMode',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OfflineEventAttendanceMode',
        'label' => 'OfflineEventAttendanceMode',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/OnlineEventAttendanceMode',
        'label' => 'OnlineEventAttendanceMode',
      ),
    ),
    'eventStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/EventCancelled',
        'label' => 'EventCancelled',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EventMovedOnline',
        'label' => 'EventMovedOnline',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/EventPostponed',
        'label' => 'EventPostponed',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/EventRescheduled',
        'label' => 'EventRescheduled',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/EventScheduled',
        'label' => 'EventScheduled',
      ),
    ),
    'evidenceLevel' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/EvidenceLevelA',
        'label' => 'EvidenceLevelA',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EvidenceLevelB',
        'label' => 'EvidenceLevelB',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/EvidenceLevelC',
        'label' => 'EvidenceLevelC',
      ),
    ),
    'fulfillmentType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/FulfillmentTypeCollectionPoint',
        'label' => 'FulfillmentTypeCollectionPoint',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/FulfillmentTypeDelivery',
        'label' => 'FulfillmentTypeDelivery',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/FulfillmentTypePickupDropoff',
        'label' => 'FulfillmentTypePickupDropoff',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/FulfillmentTypePickupInStore',
        'label' => 'FulfillmentTypePickupInStore',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/FulfillmentTypeScheduledDelivery',
        'label' => 'FulfillmentTypeScheduledDelivery',
      ),
    ),
    'hasAdultConsideration' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AlcoholConsideration',
        'label' => 'AlcoholConsideration',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/DangerousGoodConsideration',
        'label' => 'DangerousGoodConsideration',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/HealthcareConsideration',
        'label' => 'HealthcareConsideration',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/NarcoticConsideration',
        'label' => 'NarcoticConsideration',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/ReducedRelevanceForChildrenConsideration',
        'label' => 'ReducedRelevanceForChildrenConsideration',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/SexualContentConsideration',
        'label' => 'SexualContentConsideration',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/TobaccoNicotineConsideration',
        'label' => 'TobaccoNicotineConsideration',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/UnclassifiedAdultConsideration',
        'label' => 'UnclassifiedAdultConsideration',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/ViolenceConsideration',
        'label' => 'ViolenceConsideration',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/WeaponConsideration',
        'label' => 'WeaponConsideration',
      ),
    ),
    'hasDeliveryMethod' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/LockerDelivery',
        'label' => 'LockerDelivery',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OnSitePickup',
        'label' => 'OnSitePickup',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ParcelService',
        'label' => 'ParcelService',
      ),
    ),
    'hasHealthAspect' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AllergiesHealthAspect',
        'label' => 'AllergiesHealthAspect',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/BenefitsHealthAspect',
        'label' => 'BenefitsHealthAspect',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CausesHealthAspect',
        'label' => 'CausesHealthAspect',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ContagiousnessHealthAspect',
        'label' => 'ContagiousnessHealthAspect',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/EffectivenessHealthAspect',
        'label' => 'EffectivenessHealthAspect',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/GettingAccessHealthAspect',
        'label' => 'GettingAccessHealthAspect',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/HowItWorksHealthAspect',
        'label' => 'HowItWorksHealthAspect',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/HowOrWhereHealthAspect',
        'label' => 'HowOrWhereHealthAspect',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/IngredientsHealthAspect',
        'label' => 'IngredientsHealthAspect',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/LivingWithHealthAspect',
        'label' => 'LivingWithHealthAspect',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/MayTreatHealthAspect',
        'label' => 'MayTreatHealthAspect',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/MisconceptionsHealthAspect',
        'label' => 'MisconceptionsHealthAspect',
      ),
      12 => 
      array (
        'value' => 'https://schema.org/OverviewHealthAspect',
        'label' => 'OverviewHealthAspect',
      ),
      13 => 
      array (
        'value' => 'https://schema.org/PatientExperienceHealthAspect',
        'label' => 'PatientExperienceHealthAspect',
      ),
      14 => 
      array (
        'value' => 'https://schema.org/PregnancyHealthAspect',
        'label' => 'PregnancyHealthAspect',
      ),
      15 => 
      array (
        'value' => 'https://schema.org/PreventionHealthAspect',
        'label' => 'PreventionHealthAspect',
      ),
      16 => 
      array (
        'value' => 'https://schema.org/PrognosisHealthAspect',
        'label' => 'PrognosisHealthAspect',
      ),
      17 => 
      array (
        'value' => 'https://schema.org/RelatedTopicsHealthAspect',
        'label' => 'RelatedTopicsHealthAspect',
      ),
      18 => 
      array (
        'value' => 'https://schema.org/RisksOrComplicationsHealthAspect',
        'label' => 'RisksOrComplicationsHealthAspect',
      ),
      19 => 
      array (
        'value' => 'https://schema.org/SafetyHealthAspect',
        'label' => 'SafetyHealthAspect',
      ),
      20 => 
      array (
        'value' => 'https://schema.org/ScreeningHealthAspect',
        'label' => 'ScreeningHealthAspect',
      ),
      21 => 
      array (
        'value' => 'https://schema.org/SeeDoctorHealthAspect',
        'label' => 'SeeDoctorHealthAspect',
      ),
      22 => 
      array (
        'value' => 'https://schema.org/SelfCareHealthAspect',
        'label' => 'SelfCareHealthAspect',
      ),
      23 => 
      array (
        'value' => 'https://schema.org/SideEffectsHealthAspect',
        'label' => 'SideEffectsHealthAspect',
      ),
      24 => 
      array (
        'value' => 'https://schema.org/StagesHealthAspect',
        'label' => 'StagesHealthAspect',
      ),
      25 => 
      array (
        'value' => 'https://schema.org/SymptomsHealthAspect',
        'label' => 'SymptomsHealthAspect',
      ),
      26 => 
      array (
        'value' => 'https://schema.org/TreatmentsHealthAspect',
        'label' => 'TreatmentsHealthAspect',
      ),
      27 => 
      array (
        'value' => 'https://schema.org/TypesHealthAspect',
        'label' => 'TypesHealthAspect',
      ),
      28 => 
      array (
        'value' => 'https://schema.org/UsageOrScheduleHealthAspect',
        'label' => 'UsageOrScheduleHealthAspect',
      ),
    ),
    'hasTierBenefit' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/TierBenefitLoyaltyPoints',
        'label' => 'TierBenefitLoyaltyPoints',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/TierBenefitLoyaltyPrice',
        'label' => 'TierBenefitLoyaltyPrice',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/TierBenefitLoyaltyReturns',
        'label' => 'TierBenefitLoyaltyReturns',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/TierBenefitLoyaltyShipping',
        'label' => 'TierBenefitLoyaltyShipping',
      ),
    ),
    'identifyingExam' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Abdomen',
        'label' => 'Abdomen',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Appearance',
        'label' => 'Appearance',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CardiovascularExam',
        'label' => 'CardiovascularExam',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Ear',
        'label' => 'Ear',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Eye',
        'label' => 'Eye',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Genitourinary',
        'label' => 'Genitourinary',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/Head',
        'label' => 'Head',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/Lung',
        'label' => 'Lung',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/MusculoskeletalExam',
        'label' => 'MusculoskeletalExam',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/Neck',
        'label' => 'Neck',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/Neuro',
        'label' => 'Neuro',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/Nose',
        'label' => 'Nose',
      ),
      12 => 
      array (
        'value' => 'https://schema.org/Skin',
        'label' => 'Skin',
      ),
      13 => 
      array (
        'value' => 'https://schema.org/Throat',
        'label' => 'Throat',
      ),
    ),
    'imagingTechnique' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CT',
        'label' => 'CT',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/MRI',
        'label' => 'MRI',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/PET',
        'label' => 'PET',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Radiography',
        'label' => 'Radiography',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Ultrasound',
        'label' => 'Ultrasound',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/XRay',
        'label' => 'XRay',
      ),
    ),
    'incentiveStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/IncentiveStatusActive',
        'label' => 'IncentiveStatusActive',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/IncentiveStatusInDevelopment',
        'label' => 'IncentiveStatusInDevelopment',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/IncentiveStatusOnHold',
        'label' => 'IncentiveStatusOnHold',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/IncentiveStatusRetired',
        'label' => 'IncentiveStatusRetired',
      ),
    ),
    'incentiveType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/IncentiveTypeLoan',
        'label' => 'IncentiveTypeLoan',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/IncentiveTypeRebateOrSubsidy',
        'label' => 'IncentiveTypeRebateOrSubsidy',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/IncentiveTypeTaxCredit',
        'label' => 'IncentiveTypeTaxCredit',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/IncentiveTypeTaxDeduction',
        'label' => 'IncentiveTypeTaxDeduction',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/IncentiveTypeTaxWaiver',
        'label' => 'IncentiveTypeTaxWaiver',
      ),
    ),
    'infectiousAgentClass' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Bacteria',
        'label' => 'Bacteria',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Fungus',
        'label' => 'Fungus',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/MulticellularParasite',
        'label' => 'MulticellularParasite',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Prion',
        'label' => 'Prion',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Protozoa',
        'label' => 'Protozoa',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Virus',
        'label' => 'Virus',
      ),
    ),
    'itemCondition' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/DamagedCondition',
        'label' => 'DamagedCondition',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/NewCondition',
        'label' => 'NewCondition',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/RefurbishedCondition',
        'label' => 'RefurbishedCondition',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/UsedCondition',
        'label' => 'UsedCondition',
      ),
    ),
    'itemDefectReturnFees' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/FreeReturn',
        'label' => 'FreeReturn',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OriginalShippingFees',
        'label' => 'OriginalShippingFees',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/RestockingFees',
        'label' => 'RestockingFees',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ReturnFeesCustomerResponsibility',
        'label' => 'ReturnFeesCustomerResponsibility',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/ReturnShippingFees',
        'label' => 'ReturnShippingFees',
      ),
    ),
    'itemDefectReturnLabelSource' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ReturnLabelCustomerResponsibility',
        'label' => 'ReturnLabelCustomerResponsibility',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReturnLabelDownloadAndPrint',
        'label' => 'ReturnLabelDownloadAndPrint',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ReturnLabelInBox',
        'label' => 'ReturnLabelInBox',
      ),
    ),
    'legislationLegalForce' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/InForce',
        'label' => 'InForce',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/NotInForce',
        'label' => 'NotInForce',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/PartiallyInForce',
        'label' => 'PartiallyInForce',
      ),
    ),
    'legislationLegalValue' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/AuthoritativeLegalValue',
        'label' => 'AuthoritativeLegalValue',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/DefinitiveLegalValue',
        'label' => 'DefinitiveLegalValue',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/OfficialLegalValue',
        'label' => 'OfficialLegalValue',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/UnofficialLegalValue',
        'label' => 'UnofficialLegalValue',
      ),
    ),
    'mapType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ParkingMap',
        'label' => 'ParkingMap',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/SeatingMap',
        'label' => 'SeatingMap',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/TransitMap',
        'label' => 'TransitMap',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/VenueMap',
        'label' => 'VenueMap',
      ),
    ),
    'mediaAuthenticityCategory' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/DecontextualizedContent',
        'label' => 'DecontextualizedContent',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/EditedOrCroppedContent',
        'label' => 'EditedOrCroppedContent',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/OriginalMediaContent',
        'label' => 'OriginalMediaContent',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/SatireOrParodyContent',
        'label' => 'SatireOrParodyContent',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/StagedContent',
        'label' => 'StagedContent',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/TransformedContent',
        'label' => 'TransformedContent',
      ),
    ),
    'medicalAudience' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Clinician',
        'label' => 'Clinician',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/MedicalResearcher',
        'label' => 'MedicalResearcher',
      ),
    ),
    'medicalSpecialty' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Anesthesia',
        'label' => 'Anesthesia',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Cardiovascular',
        'label' => 'Cardiovascular',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CommunityHealth',
        'label' => 'CommunityHealth',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Dentistry',
        'label' => 'Dentistry',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Dermatologic',
        'label' => 'Dermatologic',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Dermatology',
        'label' => 'Dermatology',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/DietNutrition',
        'label' => 'DietNutrition',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/Emergency',
        'label' => 'Emergency',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/Endocrine',
        'label' => 'Endocrine',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/Gastroenterologic',
        'label' => 'Gastroenterologic',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/Genetic',
        'label' => 'Genetic',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/Geriatric',
        'label' => 'Geriatric',
      ),
      12 => 
      array (
        'value' => 'https://schema.org/Gynecologic',
        'label' => 'Gynecologic',
      ),
      13 => 
      array (
        'value' => 'https://schema.org/Hematologic',
        'label' => 'Hematologic',
      ),
      14 => 
      array (
        'value' => 'https://schema.org/Infectious',
        'label' => 'Infectious',
      ),
      15 => 
      array (
        'value' => 'https://schema.org/LaboratoryScience',
        'label' => 'LaboratoryScience',
      ),
      16 => 
      array (
        'value' => 'https://schema.org/Midwifery',
        'label' => 'Midwifery',
      ),
      17 => 
      array (
        'value' => 'https://schema.org/Musculoskeletal',
        'label' => 'Musculoskeletal',
      ),
      18 => 
      array (
        'value' => 'https://schema.org/Neurologic',
        'label' => 'Neurologic',
      ),
      19 => 
      array (
        'value' => 'https://schema.org/Nursing',
        'label' => 'Nursing',
      ),
      20 => 
      array (
        'value' => 'https://schema.org/Obstetric',
        'label' => 'Obstetric',
      ),
      21 => 
      array (
        'value' => 'https://schema.org/Oncologic',
        'label' => 'Oncologic',
      ),
      22 => 
      array (
        'value' => 'https://schema.org/Optometric',
        'label' => 'Optometric',
      ),
      23 => 
      array (
        'value' => 'https://schema.org/Otolaryngologic',
        'label' => 'Otolaryngologic',
      ),
      24 => 
      array (
        'value' => 'https://schema.org/Pathology',
        'label' => 'Pathology',
      ),
      25 => 
      array (
        'value' => 'https://schema.org/Pediatric',
        'label' => 'Pediatric',
      ),
      26 => 
      array (
        'value' => 'https://schema.org/PharmacySpecialty',
        'label' => 'PharmacySpecialty',
      ),
      27 => 
      array (
        'value' => 'https://schema.org/Physiotherapy',
        'label' => 'Physiotherapy',
      ),
      28 => 
      array (
        'value' => 'https://schema.org/PlasticSurgery',
        'label' => 'PlasticSurgery',
      ),
      29 => 
      array (
        'value' => 'https://schema.org/Podiatric',
        'label' => 'Podiatric',
      ),
      30 => 
      array (
        'value' => 'https://schema.org/PrimaryCare',
        'label' => 'PrimaryCare',
      ),
      31 => 
      array (
        'value' => 'https://schema.org/Psychiatric',
        'label' => 'Psychiatric',
      ),
      32 => 
      array (
        'value' => 'https://schema.org/PublicHealth',
        'label' => 'PublicHealth',
      ),
      33 => 
      array (
        'value' => 'https://schema.org/Pulmonary',
        'label' => 'Pulmonary',
      ),
      34 => 
      array (
        'value' => 'https://schema.org/Radiography',
        'label' => 'Radiography',
      ),
      35 => 
      array (
        'value' => 'https://schema.org/Renal',
        'label' => 'Renal',
      ),
      36 => 
      array (
        'value' => 'https://schema.org/RespiratoryTherapy',
        'label' => 'RespiratoryTherapy',
      ),
      37 => 
      array (
        'value' => 'https://schema.org/Rheumatologic',
        'label' => 'Rheumatologic',
      ),
      38 => 
      array (
        'value' => 'https://schema.org/SpeechPathology',
        'label' => 'SpeechPathology',
      ),
      39 => 
      array (
        'value' => 'https://schema.org/Surgical',
        'label' => 'Surgical',
      ),
      40 => 
      array (
        'value' => 'https://schema.org/Toxicologic',
        'label' => 'Toxicologic',
      ),
      41 => 
      array (
        'value' => 'https://schema.org/Urologic',
        'label' => 'Urologic',
      ),
    ),
    'medicineSystem' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Ayurvedic',
        'label' => 'Ayurvedic',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Chiropractic',
        'label' => 'Chiropractic',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/Homeopathic',
        'label' => 'Homeopathic',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Osteopathic',
        'label' => 'Osteopathic',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/TraditionalChinese',
        'label' => 'TraditionalChinese',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/WesternConventional',
        'label' => 'WesternConventional',
      ),
    ),
    'musicReleaseFormat' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CDFormat',
        'label' => 'CDFormat',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/CassetteFormat',
        'label' => 'CassetteFormat',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/DVDFormat',
        'label' => 'DVDFormat',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/DigitalAudioTapeFormat',
        'label' => 'DigitalAudioTapeFormat',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/DigitalFormat',
        'label' => 'DigitalFormat',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/LaserDiscFormat',
        'label' => 'LaserDiscFormat',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/VinylFormat',
        'label' => 'VinylFormat',
      ),
    ),
    'orderItemStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/OrderCancelled',
        'label' => 'OrderCancelled',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OrderDelivered',
        'label' => 'OrderDelivered',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/OrderInTransit',
        'label' => 'OrderInTransit',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/OrderPaymentDue',
        'label' => 'OrderPaymentDue',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/OrderPickupAvailable',
        'label' => 'OrderPickupAvailable',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/OrderProblem',
        'label' => 'OrderProblem',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/OrderProcessing',
        'label' => 'OrderProcessing',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/OrderReturned',
        'label' => 'OrderReturned',
      ),
    ),
    'orderStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/OrderCancelled',
        'label' => 'OrderCancelled',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OrderDelivered',
        'label' => 'OrderDelivered',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/OrderInTransit',
        'label' => 'OrderInTransit',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/OrderPaymentDue',
        'label' => 'OrderPaymentDue',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/OrderPickupAvailable',
        'label' => 'OrderPickupAvailable',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/OrderProblem',
        'label' => 'OrderProblem',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/OrderProcessing',
        'label' => 'OrderProcessing',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/OrderReturned',
        'label' => 'OrderReturned',
      ),
    ),
    'paymentMethodType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ByBankTransferInAdvance',
        'label' => 'ByBankTransferInAdvance',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ByInvoice',
        'label' => 'ByInvoice',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/COD',
        'label' => 'COD',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Cash',
        'label' => 'Cash',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/CheckInAdvance',
        'label' => 'CheckInAdvance',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/DirectDebit',
        'label' => 'DirectDebit',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/InStorePrepay',
        'label' => 'InStorePrepay',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/PhoneCarrierPayment',
        'label' => 'PhoneCarrierPayment',
      ),
    ),
    'permissionType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CommentPermission',
        'label' => 'CommentPermission',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReadPermission',
        'label' => 'ReadPermission',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/WritePermission',
        'label' => 'WritePermission',
      ),
    ),
    'playMode' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CoOp',
        'label' => 'CoOp',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/MultiPlayer',
        'label' => 'MultiPlayer',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/SinglePlayer',
        'label' => 'SinglePlayer',
      ),
    ),
    'pregnancyCategory' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/FDAcategoryA',
        'label' => 'FDAcategoryA',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/FDAcategoryB',
        'label' => 'FDAcategoryB',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/FDAcategoryC',
        'label' => 'FDAcategoryC',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/FDAcategoryD',
        'label' => 'FDAcategoryD',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/FDAcategoryX',
        'label' => 'FDAcategoryX',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/FDAnotEvaluated',
        'label' => 'FDAnotEvaluated',
      ),
    ),
    'priceComponentType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ActivationFee',
        'label' => 'ActivationFee',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/CleaningFee',
        'label' => 'CleaningFee',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/DistanceFee',
        'label' => 'DistanceFee',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Downpayment',
        'label' => 'Downpayment',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Installment',
        'label' => 'Installment',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Subscription',
        'label' => 'Subscription',
      ),
    ),
    'procedureType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/NoninvasiveProcedure',
        'label' => 'NoninvasiveProcedure',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/PercutaneousProcedure',
        'label' => 'PercutaneousProcedure',
      ),
    ),
    'purchaseType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/PurchaseTypeLease',
        'label' => 'PurchaseTypeLease',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/PurchaseTypeNewPurchase',
        'label' => 'PurchaseTypeNewPurchase',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/PurchaseTypeTradeIn',
        'label' => 'PurchaseTypeTradeIn',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/PurchaseTypeUsedPurchase',
        'label' => 'PurchaseTypeUsedPurchase',
      ),
    ),
    'qualifiedExpense' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/IncentiveQualifiedExpenseTypeGoodsOnly',
        'label' => 'IncentiveQualifiedExpenseTypeGoodsOnly',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/IncentiveQualifiedExpenseTypeGoodsOrServices',
        'label' => 'IncentiveQualifiedExpenseTypeGoodsOrServices',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/IncentiveQualifiedExpenseTypeServicesOnly',
        'label' => 'IncentiveQualifiedExpenseTypeServicesOnly',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/IncentiveQualifiedExpenseTypeUtilityBill',
        'label' => 'IncentiveQualifiedExpenseTypeUtilityBill',
      ),
    ),
    'refundType' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ExchangeRefund',
        'label' => 'ExchangeRefund',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/FullRefund',
        'label' => 'FullRefund',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/StoreCreditRefund',
        'label' => 'StoreCreditRefund',
      ),
    ),
    'relevantSpecialty' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/Anesthesia',
        'label' => 'Anesthesia',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/Cardiovascular',
        'label' => 'Cardiovascular',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CommunityHealth',
        'label' => 'CommunityHealth',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Dentistry',
        'label' => 'Dentistry',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Dermatologic',
        'label' => 'Dermatologic',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Dermatology',
        'label' => 'Dermatology',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/DietNutrition',
        'label' => 'DietNutrition',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/Emergency',
        'label' => 'Emergency',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/Endocrine',
        'label' => 'Endocrine',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/Gastroenterologic',
        'label' => 'Gastroenterologic',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/Genetic',
        'label' => 'Genetic',
      ),
      11 => 
      array (
        'value' => 'https://schema.org/Geriatric',
        'label' => 'Geriatric',
      ),
      12 => 
      array (
        'value' => 'https://schema.org/Gynecologic',
        'label' => 'Gynecologic',
      ),
      13 => 
      array (
        'value' => 'https://schema.org/Hematologic',
        'label' => 'Hematologic',
      ),
      14 => 
      array (
        'value' => 'https://schema.org/Infectious',
        'label' => 'Infectious',
      ),
      15 => 
      array (
        'value' => 'https://schema.org/LaboratoryScience',
        'label' => 'LaboratoryScience',
      ),
      16 => 
      array (
        'value' => 'https://schema.org/Midwifery',
        'label' => 'Midwifery',
      ),
      17 => 
      array (
        'value' => 'https://schema.org/Musculoskeletal',
        'label' => 'Musculoskeletal',
      ),
      18 => 
      array (
        'value' => 'https://schema.org/Neurologic',
        'label' => 'Neurologic',
      ),
      19 => 
      array (
        'value' => 'https://schema.org/Nursing',
        'label' => 'Nursing',
      ),
      20 => 
      array (
        'value' => 'https://schema.org/Obstetric',
        'label' => 'Obstetric',
      ),
      21 => 
      array (
        'value' => 'https://schema.org/Oncologic',
        'label' => 'Oncologic',
      ),
      22 => 
      array (
        'value' => 'https://schema.org/Optometric',
        'label' => 'Optometric',
      ),
      23 => 
      array (
        'value' => 'https://schema.org/Otolaryngologic',
        'label' => 'Otolaryngologic',
      ),
      24 => 
      array (
        'value' => 'https://schema.org/Pathology',
        'label' => 'Pathology',
      ),
      25 => 
      array (
        'value' => 'https://schema.org/Pediatric',
        'label' => 'Pediatric',
      ),
      26 => 
      array (
        'value' => 'https://schema.org/PharmacySpecialty',
        'label' => 'PharmacySpecialty',
      ),
      27 => 
      array (
        'value' => 'https://schema.org/Physiotherapy',
        'label' => 'Physiotherapy',
      ),
      28 => 
      array (
        'value' => 'https://schema.org/PlasticSurgery',
        'label' => 'PlasticSurgery',
      ),
      29 => 
      array (
        'value' => 'https://schema.org/Podiatric',
        'label' => 'Podiatric',
      ),
      30 => 
      array (
        'value' => 'https://schema.org/PrimaryCare',
        'label' => 'PrimaryCare',
      ),
      31 => 
      array (
        'value' => 'https://schema.org/Psychiatric',
        'label' => 'Psychiatric',
      ),
      32 => 
      array (
        'value' => 'https://schema.org/PublicHealth',
        'label' => 'PublicHealth',
      ),
      33 => 
      array (
        'value' => 'https://schema.org/Pulmonary',
        'label' => 'Pulmonary',
      ),
      34 => 
      array (
        'value' => 'https://schema.org/Radiography',
        'label' => 'Radiography',
      ),
      35 => 
      array (
        'value' => 'https://schema.org/Renal',
        'label' => 'Renal',
      ),
      36 => 
      array (
        'value' => 'https://schema.org/RespiratoryTherapy',
        'label' => 'RespiratoryTherapy',
      ),
      37 => 
      array (
        'value' => 'https://schema.org/Rheumatologic',
        'label' => 'Rheumatologic',
      ),
      38 => 
      array (
        'value' => 'https://schema.org/SpeechPathology',
        'label' => 'SpeechPathology',
      ),
      39 => 
      array (
        'value' => 'https://schema.org/Surgical',
        'label' => 'Surgical',
      ),
      40 => 
      array (
        'value' => 'https://schema.org/Toxicologic',
        'label' => 'Toxicologic',
      ),
      41 => 
      array (
        'value' => 'https://schema.org/Urologic',
        'label' => 'Urologic',
      ),
    ),
    'reservationStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ReservationCancelled',
        'label' => 'ReservationCancelled',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReservationConfirmed',
        'label' => 'ReservationConfirmed',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ReservationHold',
        'label' => 'ReservationHold',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ReservationPending',
        'label' => 'ReservationPending',
      ),
    ),
    'returnFees' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/FreeReturn',
        'label' => 'FreeReturn',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OriginalShippingFees',
        'label' => 'OriginalShippingFees',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/RestockingFees',
        'label' => 'RestockingFees',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ReturnFeesCustomerResponsibility',
        'label' => 'ReturnFeesCustomerResponsibility',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/ReturnShippingFees',
        'label' => 'ReturnShippingFees',
      ),
    ),
    'returnLabelSource' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/ReturnLabelCustomerResponsibility',
        'label' => 'ReturnLabelCustomerResponsibility',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReturnLabelDownloadAndPrint',
        'label' => 'ReturnLabelDownloadAndPrint',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ReturnLabelInBox',
        'label' => 'ReturnLabelInBox',
      ),
    ),
    'returnMethod' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/KeepProduct',
        'label' => 'KeepProduct',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/ReturnAtKiosk',
        'label' => 'ReturnAtKiosk',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/ReturnByMail',
        'label' => 'ReturnByMail',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/ReturnInStore',
        'label' => 'ReturnInStore',
      ),
    ),
    'returnPolicyCategory' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
        'label' => 'MerchantReturnFiniteReturnWindow',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/MerchantReturnNotPermitted',
        'label' => 'MerchantReturnNotPermitted',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/MerchantReturnUnlimitedWindow',
        'label' => 'MerchantReturnUnlimitedWindow',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/MerchantReturnUnspecified',
        'label' => 'MerchantReturnUnspecified',
      ),
    ),
    'rsvpResponse' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/RsvpResponseMaybe',
        'label' => 'RsvpResponseMaybe',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/RsvpResponseNo',
        'label' => 'RsvpResponseNo',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/RsvpResponseYes',
        'label' => 'RsvpResponseYes',
      ),
    ),
    'serverStatus' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/OfflinePermanently',
        'label' => 'OfflinePermanently',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/OfflineTemporarily',
        'label' => 'OfflineTemporarily',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/Online',
        'label' => 'Online',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/OnlineFull',
        'label' => 'OnlineFull',
      ),
    ),
    'steeringPosition' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/LeftHandDriving',
        'label' => 'LeftHandDriving',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/RightHandDriving',
        'label' => 'RightHandDriving',
      ),
    ),
    'studyDesign' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/CaseSeries',
        'label' => 'CaseSeries',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/CohortStudy',
        'label' => 'CohortStudy',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/CrossSectional',
        'label' => 'CrossSectional',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/Longitudinal',
        'label' => 'Longitudinal',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/Observational',
        'label' => 'Observational',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/Registry',
        'label' => 'Registry',
      ),
    ),
    'suitableForDiet' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/DiabeticDiet',
        'label' => 'DiabeticDiet',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/GlutenFreeDiet',
        'label' => 'GlutenFreeDiet',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/HalalDiet',
        'label' => 'HalalDiet',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/HinduDiet',
        'label' => 'HinduDiet',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/KosherDiet',
        'label' => 'KosherDiet',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/LowCalorieDiet',
        'label' => 'LowCalorieDiet',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/LowFatDiet',
        'label' => 'LowFatDiet',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/LowLactoseDiet',
        'label' => 'LowLactoseDiet',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/LowSaltDiet',
        'label' => 'LowSaltDiet',
      ),
      9 => 
      array (
        'value' => 'https://schema.org/VeganDiet',
        'label' => 'VeganDiet',
      ),
      10 => 
      array (
        'value' => 'https://schema.org/VegetarianDiet',
        'label' => 'VegetarianDiet',
      ),
    ),
    'trialDesign' => 
    array (
      0 => 
      array (
        'value' => 'https://schema.org/DoubleBlindedTrial',
        'label' => 'DoubleBlindedTrial',
      ),
      1 => 
      array (
        'value' => 'https://schema.org/InternationalTrial',
        'label' => 'InternationalTrial',
      ),
      2 => 
      array (
        'value' => 'https://schema.org/MultiCenterTrial',
        'label' => 'MultiCenterTrial',
      ),
      3 => 
      array (
        'value' => 'https://schema.org/OpenTrial',
        'label' => 'OpenTrial',
      ),
      4 => 
      array (
        'value' => 'https://schema.org/PlaceboControlledTrial',
        'label' => 'PlaceboControlledTrial',
      ),
      5 => 
      array (
        'value' => 'https://schema.org/RandomizedTrial',
        'label' => 'RandomizedTrial',
      ),
      6 => 
      array (
        'value' => 'https://schema.org/SingleBlindedTrial',
        'label' => 'SingleBlindedTrial',
      ),
      7 => 
      array (
        'value' => 'https://schema.org/SingleCenterTrial',
        'label' => 'SingleCenterTrial',
      ),
      8 => 
      array (
        'value' => 'https://schema.org/TripleBlindedTrial',
        'label' => 'TripleBlindedTrial',
      ),
    ),
  ),
);
