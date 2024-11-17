$(document).on("keydown",'textarea[name="nested_text"]',function(e){if(e.key==="Enter"&&!e.shiftKey){e.preventDefault();var i=$(this).closest("form").find('input[name="parent_id"]').val(),o=$(this).closest("form").find('input[name="post_id"]').val(),s=$(this).val(),a={_token:"{{ csrf_token() }}",parent_id:i,post_id:o,text:s};console.log(a),$.ajax({url:"/store/nested",type:"POST",data:a,success:function(t){if(t.success){console.log(t.data);let n=`
                    <div class="comments-section" id="nested-comment">
                        <div class="comment">
                            <div class="comment-header">
                                ${t.data.personal_image?`<img src="{{ asset('storage/photos/') }}/${t.data.personal_image.URL}"
                                            alt="Profile photo"
                                            class="img-fluid rounded-circle"
                                            style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`:`<img src="{{ asset('/PostBlug/default-profile .png') }}"
                                        alt="Profile photo"
                                        class="img-fluid rounded-circle"
                                        style="width: 50px; height: 50px; object-fit: fill; margin-right: 750px;">`}
                                <div style="right: 750px; font-size: 15px; margin-top: -40px; position: relative;">
                                    ${t.data.name}
                                </div>
                            </div>
                            <div class="comment-content">
                                ${t.data.comment.text}
                            </div>
                        </div>
                    </div>
                `;$("#nested-comments-section-"+i).append(n),$('textarea[id="nested-comment-'+i+'"]').val(""),$(this).closest("form").hide()}},error:function(t){console.error("Error submitting reply comment:",t)}})}});
