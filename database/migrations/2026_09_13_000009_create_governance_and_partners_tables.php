

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', [
                'government_and_authorities',
                'financial_institutions',
                'ev_companies',
                'colleges_and_research',
                'development_organisations',
                'employers_and_logistics',
                'investors',
                'other'
            ])->default('other');
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            $table->string('collaboration_area')->nullable();
            $table->enum('status', ['active', 'strategic_partner', 'past'])->default('active');
            $table->date('start_date')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('order_number')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('category');
        });

        Schema::create('partnership_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_name');
            $table->string('category')->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('collaboration_interests')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'under_review', 'contacted', 'accepted', 'archived'])->default('new');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_enquiries');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('committee_members');
        Schema::dropIfExists('committees');
        Schema::dropIfExists('leaders');
        Schema::dropIfExists('governance_bodies');
    }
};
