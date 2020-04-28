import axios from 'axios';

const apiClient = axios.create();

const token = '8d65d23234545bee5ebb50cd22a9b3';

const prepareProduct = (product, key) => {
  const result = {
    ...product,
    categoryId: key,
  };

  if (product.sizes) {
    result.sizes = product.sizes.map((size) => {
      const _size = size.value;
      const options = _size.options;
      if (options) {
        _size.options = options.map((option) => option.value);
      }
      return _size;
    });
  }

  return result;
};

async function getCollectionByKey(key) {
  const { data } = await apiClient({
    method: 'post',
    url: `/cockpit/api/collections/get/${key}?token=${token}`,
    data: {
      filter: {
        published: true,
      },
    },
  });
  return data.entries.map((entry) => prepareProduct(entry, key));
}

async function getSingletonByKey(key) {
  const { data } = await apiClient({
    method: 'post',
    url: `/cockpit/api/singletons/get/${key}?token=${token}`,
  });
  return data;
}

async function sendForm(form) {
  const { data } = await apiClient({
    method: 'post',
    url: '/order.php',
    data: form,
  });
  return data;
}

export { getCollectionByKey, getSingletonByKey, sendForm };
