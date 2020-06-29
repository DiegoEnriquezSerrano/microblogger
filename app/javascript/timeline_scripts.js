if (paths[0] == 'timeline') {
  getDirectoryLinks[0].classList.toggle('active');
} else if (paths[0] == 'published') {
  getDirectoryLinks[1].classList.toggle('active');
} else if (paths[0] == 'drafts') {
  getDirectoryLinks[2].classList.toggle('active');
} else if (paths[0] == 'liked') {
  getDirectoryLinks[3].classList.toggle('active');
}

const createPostModal = _q('.createPostModal');
const createPostButton = _q('.createPost');
const createPostContainer = _q('#postContainer');
const createPostForm = createPostModal._q('.postForm');

createPostButton.onclick = (e) => {
  createPostModal.classList.toggle('open');
}

createPostModal._q('.close').onclick = () => {
  modalClose(createPostModal);
};