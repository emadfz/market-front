<div role="tabpanel" class="tab-pane active" id="tabproduct">
    {!!html_entity_decode($product->description)!!}
        <div class="titlewith-table">
          <div class="body-semibold">Other Specification</div>
          <table class="table table-bordered nearby-table mobile-table">
            <tbody>
                 @foreach($productnonvariant->productNonVariantAttribute as $product_nonvar) 
              <tr>
                <td class="col1">{{$product_nonvar->attribute->attribute_name}}</td>
                <td class="col2" data-title="Shape">{{$product_nonvar->attribute->AttributeValues[0]->attribute_values}}</td>
              </tr>
                @endforeach
            </tbody>
          </table>
        </div>
    <a href="#" class="btn btn-block showmore" title="Show More">show more</a>
</div>