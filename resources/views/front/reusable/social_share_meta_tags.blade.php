@push('metaTagsSocialShare')
<!-- Open Graph data -->
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ $model->name }}" />
<meta property="og:image" content="{{ env('APP_ADMIN_URL').'/images/products/thumbnail/'.@$model->Files[0]->path }}" />
<meta property="og:image:width" content="149" />
<meta property="og:image:height" content="84" />
<meta property="og:url" content="{{ request()->fullUrl() }}" />
<meta property="og:description" content="{{ $model->description }}" />
<meta property='og:site_name' content='inSpree Online Marketplace'/>
<!--<meta property="fb:admins" content="Facebook numeric ID" />-->

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $model->name }}" />
<!--<meta name="twitter:site" content="@publisher_handle">-->
<!--<meta name="twitter:creator" content="@author_handle">-->
<meta name="twitter:url" content="{{ request()->fullUrl() }}" />
<meta name="twitter:image" content="{{ env('APP_ADMIN_URL').'/images/products/thumbnail/'.@$model->Files[0]->path }}" />
<meta name="twitter:description" content="{{ $model->description }}" />

<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $model->name }}" />
<meta itemprop="description" content="{{ $model->description }}" />
<meta itemprop="image" content="{{ env('APP_ADMIN_URL').'/images/products/thumbnail/'.@$model->Files[0]->path }}" />

@endpush