<style>
    h3.resultsCount{
		font-size:24px;
		text-decoration:underline;
		text-align:center;
                
	
	}
    div.websiteContainer{
        margin-left:auto;
        margin-right:auto;
        width:600px;
        display:block;
        margin-bottom:40px;
    }

    div.websiteContainer .title{
        display:inline-block;	
    }
    div.websiteContainer .value{
        float:right;
    }

    div.websiteContainer .websiteName{
        text-align:center;
        font-size:20px;
        font-weight:bold;
        margin-bottom:5px;
    }
    div.websiteContainer .websiteName a{
        text-decoration:none;
        color:#555;
    }
    div.websiteContainer .descriptionItem{
        margin-bottom:4px;
        padding-bottom:1px;
        display:block;
        border-bottom-color:#CCC;
        border-bottom-width:1px;
        border-bottom-style:solid;
    }
    div.websiteContainer .descriptionItem.important{
        font-weight:bold !important;
    }
</style>
<h3 class="resultsCount"><? echo count($this->result['sites']); ?> sites found:</h3>
<? foreach ($this->result['sites'] as $site) :?>
<div class="websiteContainer">
    <div class="websiteName"><a href="/sites/site?id=<? echo $site['site_id']; ?>"><? echo $site['siteHost']; ?></a></div>
    <div class="descriptionItem">
        <div class="title">Category</div>
        <div class="value"><? echo $site['siteCategory']; ?></div>
    </div>
    <div class="websiteDateAdded">
        <div class="title">Added on</div>
        <div class="value"><? echo $site['dateAdded']; ?></div>
    </div>
</div>
<? endforeach; ?>


 
    