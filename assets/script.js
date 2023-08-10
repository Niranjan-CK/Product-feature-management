// filter project by title
jQuery(document).ready(function($) {
    // upload multiple file for project
    jQuery(document).on("click", '.vote-on-behalf', function(event) { 
        event.preventDefault();
        document.getElementById('popupBox').style.display = 'block';
        document.getElementById("popup_btn").value = event.target.id;
    });

    $('#filter_project_date').on('change',function(){
        var selectedValue = $(this).val();

        // Do something with the selected value
        console.log(selectedValue); 
        if(selectedValue == "custom")
        {
            date_filter.style.display = 'block';
        }
        else
        {
            date_filter.style.display = 'none';
        }
    })


    jQuery(document).on("click",'#popup_btn',function(event){
        event.preventDefault();
        var vote_reference = $('#vote_reference_by_user').val();
        var feature_id = $(this).val();
        console.log(vote_reference+" = "+feature_id);
        var action = "vote_by_rerference";

        $.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data:{
                action :action,
                vote_reference : vote_reference,
                feature_id : feature_id,
                nonce : wp_feature_select.nonces.wt_ddfw_nonce

            },
            success: function(response) {
                // Update the content of the 'like'+id element with the PHP file content  
                console.log(response);
                // document.location.href="/feature";
                
                var parts = feature_id.split('_');
                var numericPart = parts[1];
               document.getElementById("popupBox").style.display="none";
               document.getElementById("like"+numericPart).textContent=response;
               document.getElementById(feature_id).style.display = "none";
               document.getElementById('vote_reference_by_user').value="";

               


            },
            error: function(xhr, status, error) {
                try{
                    // Remove the leading "3" from the response text
                    var jsonString = xhr.responseText.substring(1);
            
                    // Parse the extracted JSON string
                    var responseJSON = JSON.parse(jsonString);
                    
                    // Extract the error message from the parsed JSON
                    var errorMessage = responseJSON.data.message;
                
                    alert(errorMessage);
                } catch (e) {
                    console.error("Error parsing JSON response:", e);
                    console.log("Raw response:", responseText);
                }
            }

        })



    })

    
    var abc = 0; // Global variable to keep track of file inputs

$('body').on('change', '#file', function() {
  if (this.files && this.files[0]) {
    abc += 1; // Increment the global variable by 1
    var z = abc - 1;
    var x = $(this).parent().find('#previewimg' + z).remove();
    $(this).before("<div class='col' id='abcd" + abc + "' class='abcd'><img style='width: 50px; height: 50px;' id='previewimg" + abc + "' src=''/></div>");

    var reader = new FileReader();
    reader.onload = imageIsLoaded;
    reader.readAsDataURL(this.files[0]);

    $(this).hide();
    $("#abcd" + abc).append($("<span/>", {
      id: 'img',
    }).click(function() {
      $(this).parent().parent().remove();
    }));

    // Append the SVG icon to the button
    var svgIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi delete-btn bi-x-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
      </svg>
    `;

    $("#img").append(svgIcon);
  }
});

  
    // Image preview
    function imageIsLoaded(e) {
      $('#previewimg' + abc)
        .attr('src', e.target.result);
    };
});

var abc = 0; // Declaring and defining global increment variable.
jQuery(document).on("click", '#add_more', function(event) { 
    console.warn('add_more');
    jQuery(this).after(jQuery("<div/>", {
        id: 'filediv',
        class: 'col'
    }).fadeIn('slow').append(jQuery("<input/>", {
        name: 'file[]',
        type: 'file',
        id: 'file',
        style:'display:none;'
        
    })))
                
});

    // Add new project
    jQuery(document).on("click", '#add_new', function(event) { 
   
        // Perform an AJAX request to load the PHP file content
        $.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data: {
                action: 'back_to_home',
            },
            success: function(response) {
                // Update the content of the 'left_side' div with the PHP file content
                $('#left_side').html(response);
            },
            error: function() {
                alert( wp.i18n.__('An error occurred while loading the PHP file content.'));
            }
        });

        // Perform any additional changes to the 'content-area' div
        $.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data: {
                action: 'add_new_idea',
            },
            success: function(response) {
                // Update the content of the 'left_side' div with the PHP file content
                $('#content-area').html(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });


    // Add Vote
    function vote(event) {
        event.preventDefault();
        var action = 'feature_vote';
        var id = event.target.id;
        console.log(event)
        // Perform an AJAX request to load the PHP file content
        jQuery.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            
            data:{
                action :action,
                vote:id,
                nonce : wp_feature_select.nonces.wt_ddfw_nonce

            },
            success: function(response) {
                // Update the content of the 'like'+id element with the PHP file content  
                console.log(response);
                document.getElementById('like'+id).textContent=response;
                document.getElementById(id).textContent = "VOTED";
                document.getElementById('bg'+id).style.backgroundColor = "#0d6efd";
                document.getElementById(id).style.color = "#fff";
                document.getElementById('bg'+id).style.border = "0px";
                document.getElementById('svg'+id).style.fill = "#fff ";

            },
            
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error)
            }
        });
    }

    function unvote(event) {
        event.preventDefault();
        var action = 'feature_unvote';
        var id = event.target.id;
        console.log(event)
        // Perform an AJAX request to load the PHP file content
        jQuery.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data:{
                action :action,
                vote:id,
                nonce : wp_feature_select.nonces.wt_ddfw_nonce

            },
            success: function(response) {
                // Update the content of the 'like'+id element with the PHP file content  
                console.log(response);
                document.getElementById('like'+id).textContent=response;
                document.getElementById(id).textContent = "Vote";
                document.getElementById('bg'+id).style.backgroundColor = "#fff";
                document.getElementById(id).style.color = "#000";
                document.getElementById('bg'+id).style.border = "1px solid gray";
                document.getElementById('svg'+id).style.fill = "#000";


         
            },
            
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(error)
            }
        });
    }

    // hide and show vote on behalf button
    function vote_on_behalf(event,vote_id)
    {
        event.preventDefault();
        console.log(vote_id)
        var contentDiv = vote_id;
        if (contentDiv.style.display === 'none') {
            contentDiv.style.display = 'block'; // Show the div
        } else {
            contentDiv.style.display = 'none'; // Hide the div
        }
    }


    // add reference 
    function add_reference(event)
    { 
        
        event.preventDefault();
        console.log(event.target.id)
        document.getElementById('popupBox').style.display = 'block';
        document.getElementById("popup_btn").value = event.target.id;

    }
    jQuery(document).on("click", '#closePopupButton', function(event) { 

        document.getElementById('popupBox').style.display = 'none';
      });



    //   feature list 

   function edit_feature(event)
   {
        // back to home
        jQuery.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data: {
                action: 'back_to_home',
            },
            success: function(response) {
                // Update the content of the 'left_side' div with the PHP file content
                $('#left_side').html(response);
            },
            error: function() {
                alert( wp.i18n.__('An error occurred while loading the PHP file content.'));
            }

       
        });
     // edit product

        // Perform any additional changes to the 'content-area' div
        jQuery.ajax({
            url: wp_feature_select.ajax_url,
            type: 'POST',
            data: {
                action: 'edit_feature_idea',
                feature : event.target.id,
                nonce : wp_feature_select.nonces.wt_ddfw_nonce
            },
            success: function(response) {
                // Update the content of the 'left_side' div with the PHP file content
                $('#content-area').html(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });


   }


  function deletefun(event,image,fd,images){
    event.preventDefault();
    var image_id = image;
    var feature_id = fd;
    console.log(images)

    console.log(JSON.stringify(images))

   

    jQuery.ajax({
        url: wp_feature_select.ajax_url,
        type: 'POST',
        data: {
            action: 'delete_image',
            feature : feature_id,
            image_id : image_id,
            images:images,
            nonce : wp_feature_select.nonces.wt_ddfw_nonce
        },
        success: function(response) {
            // Update the content of the 'left_side' div with the PHP file content
            $('#images').html(response);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });



  }

