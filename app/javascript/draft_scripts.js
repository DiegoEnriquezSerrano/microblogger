if (paths[0] === 'draft') {

  const postForm = _q('.postForm');
  const draftToggle = draftToggles[0]._q('.draftActive');
  const postBody = postForm._q('#postBody');
  const postSubmit = postForm._q('#postSubmit');
  const postPreview = postForm._q('#postPreview');
  const childPost = postForm._q('.smallPost') || false;

  postSubmit.onclick = (e) => {
    let submit = e.target;
    let body = {
      draft: draftToggle.value == 1 ? false : true,
      text: postBody.value,
      draftId: Number(paths[1]),
      isRepost: childPost ? 1 : 0,
      repostFromPostId: childPost ? childPost.dataset.postid : null,
    };
    let url = homeDirectory + "app/api/post.php";
    let params = {
      method: 'POST',
      body: JSON.stringify(body), 
      headers: { "Content-Type": "application/json" }
    };

    fetch(url, params)
    .then(response => {return response.text()})
    .then(data =>  {
      console.log(data);
    });
  };
};