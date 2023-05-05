# 使い方

コンテンツ追加参考
```
{% if ProductShortCodes is not empty %}
{% for ProductShortCode in ProductShortCodes %}
<a href="{{ url('product_detail', {'id': ProductShortCode.Product.id}) }}">
    <h1>{{ ProductShortCode.Product.name }}</h1>
    <p class="img"><img src="{{ asset(ProductShortCode.Product.main_list_image|no_image_product, 'save_image') }}"></p>
    <p class="price02-default">
        {% if ProductShortCode.Product.hasProductClass %}
            {% if ProductShortCode.Product.getPrice02Min == ProductShortCode.Product.getPrice02Max %}
                {{ ProductShortCode.Product.getPrice02IncTaxMin|price }}
            {% else %}
                {{ ProductShortCode.Product.getPrice02IncTaxMin|price }} ～ {{ ProductShortCode.Product.getPrice02IncTaxMax|price }}
            {% endif %}
        {% else %}
            {{ ProductShortCode.Product.getPrice02IncTaxMin|price }}
        {% endif %}
    </p>
</a>
{% endfor %}
{% endif %}
```

トップや固定ページで呼び出し参考 twig
1はid
```
{{getProductShortCode(1)|raw}}
```
