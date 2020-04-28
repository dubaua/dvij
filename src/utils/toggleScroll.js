function freeze() {
  document.body.style.top = `-${window.scrollY}px`;
  document.body.style.position = 'fixed';
}

function unfreeze() {
  const scrollY = document.body.style.top;
  document.body.style.position = '';
  document.body.style.top = '';
  window.scrollTo(0, parseInt(scrollY) * -1);
}

function toggleScroll(force) {
  if (force !== undefined) {
    if (force) {
      freeze();
    } else {
      unfreeze();
    }
  } else {
    const scrollY = document.body.style.top;
    if (scrollY) {
      unfreeze();
    } else {
      freeze();
    }
  }
}

export default toggleScroll;
