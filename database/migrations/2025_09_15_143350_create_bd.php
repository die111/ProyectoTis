<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('allows_groups')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('competition_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_grade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('educational_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('department');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('competitors', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('id_document')->unique();
            $table->string('legal_guardian_contact');
            $table->foreignId('educational_institution_id')->constrained('educational_institutions');
            $table->string('school_grade');
            $table->string('academic_tutor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('area_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['manager', 'evaluator'])->default('evaluator');
            $table->timestamps();

            $table->unique(['area_id', 'user_id']);
        });

        Schema::create('area_competition_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('competition_level_id')->constrained('competition_levels')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['area_id', 'competition_level_id']);
        });

        Schema::create('competition_phases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competitor_id')->constrained('competitors');
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('competition_level_id')->constrained('competition_levels');
            $table->foreignId('phase_id')->constrained('competition_phases');
            $table->enum('participation_type', ['individual', 'group'])->default('individual');
            $table->string('group_name')->nullable();
            $table->json('group_members')->nullable();
            $table->enum('status', ['registered', 'qualified', 'not_qualified', 'disqualified', 'awarded'])->default('registered');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['competitor_id', 'area_id', 'phase_id']);
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations');
            $table->foreignId('evaluator_id')->constrained('users');
            $table->decimal('score', 5, 2);
            $table->text('comments')->nullable();
            $table->boolean('ethical_compliance')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('medal_stand_parameters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('competition_level_id')->constrained('competition_levels');
            $table->integer('golds')->default(1);
            $table->integer('silvers')->default(2);
            $table->integer('bronzes')->default(3);
            $table->integer('honor_mentions')->default(5);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['area_id', 'competition_level_id']);
        });

        Schema::create('workflow_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('competition_level_id')->constrained('competition_levels');
            $table->boolean('evaluation_started')->default(false);
            $table->boolean('evaluation_finished')->default(false);
            $table->boolean('qualified_published')->default(false);
            $table->boolean('awarded_published')->default(false);
            $table->boolean('certificates_generated')->default(false);
            $table->timestamps();

            $table->unique(['area_id', 'competition_level_id']);
        });


        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations');
            $table->decimal('final_score', 5, 2);
            $table->integer('position')->nullable();
            $table->enum('award', ['gold', 'silver', 'bronze', 'honor_mention', 'participation'])->nullable();
            $table->boolean('manager_approval')->default(false);
            $table->foreignId('area_manager_id')->nullable()->constrained('users');
            $table->timestamp('approval_date')->nullable();
            $table->timestamps();

            $table->unique(['registration_id']);
        });

        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations');
            $table->foreignId('user_id')->constrained('users');
            $table->text('description');
            $table->enum('status', ['pending', 'under_review', 'resolved', 'rejected'])->default('pending');
            $table->text('resolution')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('action');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('claims');
        Schema::dropIfExists('workflow_configurations');
        Schema::dropIfExists('results');
        Schema::dropIfExists('medal_stand_parameters');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('registrations');
        Schema::dropIfExists('competition_phases');
        Schema::dropIfExists('area_competition_level');
        Schema::dropIfExists('area_user');
        Schema::dropIfExists('competitors');
        Schema::dropIfExists('educational_institutions');
        Schema::dropIfExists('competition_levels');
        Schema::dropIfExists('areas');
    }
};
