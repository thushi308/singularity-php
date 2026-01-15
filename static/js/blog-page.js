import blogData  from "./blog-data.json" with {type : "json"};

const container = document.querySelector(".blogs-container");

const oldCard = document.querySelector(".blog-card");
if (oldCard) oldCard.remove();

blogData.forEach((blog) => {
  const card = document.createElement("section");
  card.className = "blog-card";

  card.innerHTML = `
    <div class="horizontal-line"></div>

    <div class="blog-left">
      <h2 class="blog-title">${blog.title}</h2>
      <p class="blog-description">${blog.description}</p>
      <a href="${blog.link}" class="read-more">
        Read more
        <span class="arrow">
          <span class="arrow-default">❯</span>
          <span class="arrow-hover">➔</span>
        </span>
      </a>
    </div>

    <div class="blog-right">
      <div class="image-box">
        <img src="${blog.image}" alt="article image" />
      </div>

      <div class="author-box">
        <img src="${blog.authorImg}" alt="author" class="author-img" />
        <div>
          <p class="author-name">${blog.authorName}</p>
          <p class="author-role">${blog.authorRole}</p>
          <p class="date">${blog.date}</p>
        </div>
      </div>
    </div>
  `;

  container.appendChild(card);
});
