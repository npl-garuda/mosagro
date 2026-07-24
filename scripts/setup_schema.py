#!/usr/bin/env python3
from pathlib import Path
base = Path('/var/www/mosagro')
mig = base / 'database/migrations'
pc = mig / '2026_07_24_135149_create_product_category_table.php'
pc.write_text("""<?php
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('product_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort')->nullable();
            $table->unique(['product_id', 'category_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('product_category'); }
};
""")
schemas = {
'brands': """Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });""",
'categories': """Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });""",
'products': """Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('composition')->nullable();
            $table->text('application')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_exclusive')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });""",
'packagings': """Schema::create('packagings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });""",
'document_sections': """Schema::create('document_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });""",
'documents': """Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->foreignId('document_section_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });""",
'partner_brands': """Schema::create('partner_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });""",
'lead_recipients': """Schema::create('lead_recipients', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });""",
'leads': """Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('price_request');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('inn')->nullable();
            $table->string('city')->nullable();
            $table->text('comment')->nullable();
            $table->string('contact_preference')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();
        });""",
}
tpl = """<?php
use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;
return new class extends Migration {{
    public function up(): void {{
        {body}
    }}
    public function down(): void {{
        Schema::dropIfExists('{table}');
    }}
}};
"""
for key, body in schemas.items():
    matches = list(mig.glob(f'*_create_{key}_table.php'))
    if not matches:
        raise SystemExit(f'missing migration for {key}')
    matches[0].write_text(tpl.format(body=body, table=key))
    print('wrote', matches[0].name)
models = {
'Brand': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;
class Brand extends Model {
    protected $fillable = ['name','slug','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function documentSections(): HasMany { return $this->hasMany(DocumentSection::class); }
}
""",
'Category': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany;
class Category extends Model {
    protected $fillable = ['name','slug','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_category')
            ->withPivot(['is_primary','sort']);
    }
}
""",
'Product': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany;
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;
class Product extends Model {
    protected $fillable = ['brand_id','name','slug','short_description','composition','application','image','is_new','is_exclusive','is_active','sort'];
    protected function casts(): array {
        return ['is_new'=>'boolean','is_exclusive'=>'boolean','is_active'=>'boolean'];
    }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'product_category')
            ->withPivot(['is_primary','sort']);
    }
    public function packagings(): HasMany { return $this->hasMany(Packaging::class); }
    public function documents(): HasMany { return $this->hasMany(Document::class); }
}
""",
'Packaging': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
class Packaging extends Model {
    protected $fillable = ['product_id','name','sort'];
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
""",
'DocumentSection': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
use Illuminate\\Database\\Eloquent\\Relations\\HasMany;
class DocumentSection extends Model {
    protected $fillable = ['name','slug','description','brand_id','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function documents(): HasMany { return $this->hasMany(Document::class); }
}
""",
'Document': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;
class Document extends Model {
    protected $fillable = ['title','file_path','external_url','document_section_id','product_id','sort'];
    public function section(): BelongsTo { return $this->belongsTo(DocumentSection::class, 'document_section_id'); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
""",
'PartnerBrand': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
class PartnerBrand extends Model {
    protected $fillable = ['name','logo','url','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
""",
'LeadRecipient': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
class LeadRecipient extends Model {
    protected $fillable = ['email','is_active','sort'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
""",
'Lead': """<?php
namespace App\\Models;
use Illuminate\\Database\\Eloquent\\Model;
class Lead extends Model {
    protected $fillable = ['type','name','phone','email','inn','city','comment','contact_preference','status'];
}
""",
}
for name, content in models.items():
    (base / f'app/Models/{name}.php').write_text(content)
    print('model', name)
print('done')
