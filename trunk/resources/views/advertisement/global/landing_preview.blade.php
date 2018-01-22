 <style>
    /* Prevents slides from flashing */
    #slidespreview {
      display:none;
    }
    #slidespreview li{
        display: hidden;
    }
  </style>
<?php 
	$a1style = "width: 100%; height: 450px;";
	$a1url = "";
	if(!is_null($a1)){
		$a1style .= " cursor: pointer; background-image: url('" .asset('/') . "images/advertisement/" . $a1->id ."/" . $a1->path . "'); background-size: cover;";
		$a1url = URL::to('/') . "/" . $a1->url;
	}
	$d1style = "height: 250px;";
	$d1url = "";
	if(!is_null($d1)){
		$d1style .= " cursor: pointer; background-image: url('" .asset('/') . "images/advertisement/" . $d1->id ."/" . $d1->path . "'); background-size: cover;";
		$d1url = URL::to('/') . "/" . $d1->url;
	}
	$d2style = "height: 250px;";
	$d2url = "";
	if(!is_null($d2)){
		$d2style .= " cursor: pointer; background-image: url('" .asset('/') . "images/advertisement/" . $d2->id ."/" . $d2->path . "'); background-size: cover;";
		$d2url = URL::to('/') . "/" . $d2->url;
	}
	$d3style = "height: 250px;";
	$d3url = "";
	if(!is_null($d3)){
		$d3style .= " cursor: pointer; background-image: url('" .asset('/') . "images/advertisement/" . $d3->id ."/" . $d3->path . "'); background-size: cover;";
		$d3url = URL::to('/') . "/" . $d3->url;
	}
	$d4style = "height: 250px;";
	$d4url = "";
	if(!is_null($d4)){
		$d4style .= " cursor: pointer; background-image: url('" .asset('/') . "images/advertisement/" . $d4->id ."/" . $d4->path . "'); background-size: cover;";
		$d4url = URL::to('/') . "/" . $d4->url;
	}
?>
<div class="a1" id="a1" style="{{$a1style}}">
	   <div id="slidespreview" style="padding-top:0;margin-top:0;border:0px solid red; background-color: #FFF;">
        @foreach($images as $i)
			<?php $imgpath='/images/advertisement/'.$i->id.'/'.$i->path; ?>
			<img src="{{asset($imgpath)}}" class="urlredirect" urlrel="{{URL::to('/')}}/{{$i->url}}" width="100%;" height="100%;"
			style="margin-top:0;padding-top:0;object-fit:contain;">
        @endforeach
    </div>
</div>
<div class="clearfix"></div>
<div class="col-sm-3 no-padding urlredirect" urlrel="{{$d1url}}" id="d1" style="{{$d1style}}">

</div>
<div class="col-sm-3 no-padding urlredirect" urlrel="{{$d2url}}" id="d2" style="{{$d2style}}">

</div>
<div class="col-sm-3 no-padding urlredirect" urlrel="{{$d3url}}"" id="d3" style="{{$d3style}}">

</div>
<div class="col-sm-3 no-padding urlredirect" urlrel="{{$d4url}}" id="d4" style="{{$d4style}}">

</div>
<script>
    $(document).ready(function () {
		$('.urlredirect').on('click', function() {
			var url = $(this).attr("urlrel");
			if(url != ""){
				window.open(url, '_blank');
			}
		});
        $("#slidespreview").slidesjs({
            width:1028,
            height:450,
            navigation:{
                active:true,
                effect:"slide"
            },
            play: {
              active: true,
                // [boolean] Generate the play and stop buttons.
                // You cannot use your own buttons. Sorry.
              effect: "slide",
                // [string] Can be either "slide" or "fade".
              interval:5000,
                // [number] Time spent on each slide in milliseconds.
              auto:true,
                // [boolean] Start playing the slideshow on load.
              swap: true,
                // [boolean] show/hide stop and play buttons
              pauseOnHover: false,
                // [boolean] pause a playing slideshow on hover
              restartDelay: 2500
                // [number] restart delay on inactive slideshow
            },
            callback: {
                loaded: function(number) {
                    $('.slidesjs-pagination, .slidesjs-navigation').hide(0); 
                }
            }
        });
    });
</script>
