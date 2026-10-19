/* Buyer: AJAX search / filter (cards match PHP renderListingCard) */
(function () {
    'use strict';
    var btn = document.getElementById('searchBtn');
    if (!btn) return;

    var input    = document.getElementById('searchInput');
    var category = document.getElementById('categoryFilter');
    var results  = document.getElementById('searchResults');
    var defaults = document.getElementById('defaultListings');
    var timer;

    // Title / category come pre-escaped from ajax/buyer_search.php
    function card(l) {
        var price = Number(l.current_bid).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        var link  = 'details.php?id=' + encodeURIComponent(l.id);
        return '' +
        '<article class="listing-card">' +
            '<a class="listing-media" href="' + link + '">' +
                '<img src="' + l.image_url + '" alt="" loading="lazy">' +
                '<span class="chip chip-cat">' + (l.category_name || '') + '</span>' +
                '<span class="chip chip-time" data-end="' + l.end_datetime + '"><span class="t">--</span></span>' +
            '</a>' +
            '<div class="listing-body">' +
                '<h3 class="listing-title"><a href="' + link + '">' + l.title + '</a></h3>' +
                '<div class="listing-meta">' +
                    '<div><span class="label">Current bid</span><span class="price">$' + price + '</span></div>' +
                    '<div class="right"><span class="label">Bids</span><span class="num">' + (l.bid_count || 0) + '</span></div>' +
                '</div>' +
            '</div>' +
            '<div class="listing-foot"><a href="' + link + '" class="btn btn-primary btn-sm">View &amp; bid</a></div>' +
        '</article>';
    }

    function search() {
        var q = input.value.trim();
        var c = category.value;
        var filtered = q !== '' || c !== '';

        var xhr = new XMLHttpRequest();
        xhr.open('GET', '../../ajax/buyer_search.php?search=' + encodeURIComponent(q) + '&category=' + encodeURIComponent(c), true);
        xhr.onload = function () {
            if (this.status !== 200) return;
            var listings;
            try { listings = JSON.parse(this.responseText); } catch (e) { return; }

            if (defaults) defaults.hidden = filtered;

            if (!filtered && defaults) { results.innerHTML = ''; return; }

            if (!listings.length) {
                results.innerHTML =
                    '<div class="empty-state"><h3>No auctions found</h3>' +
                    '<p>Try a different keyword or category.</p></div>';
                return;
            }
            results.innerHTML = '<div class="listings-grid">' + listings.map(card).join('') + '</div>';
            if (window.refreshCountdowns) window.refreshCountdowns();
        };
        xhr.send();
    }

    btn.addEventListener('click', search);
    input.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); search(); } });
    input.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(search, 350); });
    category.addEventListener('change', search);
})();
