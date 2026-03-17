<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PpstIndicatorsSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            'Teacher I – MT I' => [
                1 => 'Apply knowledge of content within and across curriculum teaching areas.',
                2 => 'Use research-based knowledge and principles of teaching and learning to enhance professional practice.',
                3 => 'Ensure the positive use of ICT to facilitate the teaching and learning process.',
                4 => 'Use a range of teaching strategies that enhance learner achievement in literacy and numeracy skills.',
                5 => 'Apply a range of teaching strategies to develop critical and creative thinking, as well as other higher-order thinking skills.',
                6 => 'Display proficient use of Mother Tongue, Filipino and English to facilitate teaching and learning.',
                7 => 'Use effective verbal and non-verbal classroom communication strategies to support learner understanding, participation, engagement and achievement.',
                // Domain 2
                8 => 'Establish safe and secure learning environments to enhance learning through the consistent implementation of policies, guidelines and procedures.',
                9 => 'Maintain learning environments that promote fairness, respect and care to encourage learning.',
                10 => 'Manage classroom structure to engage learners, individually or in groups, in meaningful exploration, discovery and hands-on activities within a range of physical learning environments.',
                11 => 'Maintain supportive learning environments that nurture and inspire learners to participate, cooperate and collaborate in continued learning.',
                12 => 'Apply a range of successful strategies that maintain learning environments that motivate learners to work productively by assuming responsibility for their own learning.',
                13 => 'Manage learner behavior constructively by applying positive and non-violent discipline to ensure learning-focused environments.',
                // Domain 3
                14 => 'Use differentiated, developmentally appropriate learning experiences to address learners’ gender, needs, strengths, interests and experiences.',
                15 => 'Establish a learner-centered culture by using teaching strategies that respond to learners’ linguistic, cultural, socio-economic and religious backgrounds.',
                16 => 'Design, adapt and implement teaching strategies that are responsive to learners with disabilities, giftedness and talents.',
                17 => 'Plan and deliver teaching strategies that are responsive to the special educational needs of learners in difficult circumstances, including: geographic isolation; chronic illness; displacement due to armed conflict, urban resettlement or disasters; child abuse and child labor practices.',
                18 => 'Adapt and use culturally appropriate teaching strategies to address the needs of learners from indigenous groups.',
                // Domain 4
                19 => 'Plan, manage and implement developmentally sequenced teaching and learning processes to meet curriculum requirements and varied teaching contexts.',
                20 => 'Set achievable and appropriate learning outcomes that are aligned with learning competencies.',
                21 => 'Adapt and implement learning programs that ensure relevance and responsiveness to the needs of all learners.',
                22 => 'Participate in collegial discussions that use teacher and learner feedback to enrich teaching practice.',
                23 => 'Select, develop, organize and use appropriate teaching and learning resources, including ICT, to address learning goals.',
                // Domain 5
                24 => 'Design, select, organize and use diagnostic, formative, and summative assessment strategies consistent with curriculum requirements.',
                25 => 'Monitor and evaluate learner progress and achievement using learner attainment data.',
                26 => 'Use strategies for providing timely, accurate and constructive feedback to improve learner performance.',
                27 => 'Communicate promptly and clearly the learners’ needs, progress and achievement to key stakeholders, including parents/guardians.',
                28 => 'Utilize assessment data to inform the modification of teaching and learning practices and programs.',
                // Domain 6
                29 => 'Maintain learning environments that are responsive to community contexts.',
                30 => 'Build relationships with parents/guardians and the wider school community to facilitate involvement in the educative process.',
                31 => 'Review regularly personal teaching practice using existing laws and regulations that apply to the teaching profession and the responsibilities specified in the Code of Ethics for Professional Teachers.',
                32 => 'Comply with and implement school policies and procedures consistently to foster harmonious relationships with learners, parents, and other stakeholders.',
                // Domain 7
                33 => 'Apply a personal philosophy of teaching that is learner-centered.',
                34 => 'Adopt practices that uphold the dignity of teaching as a profession by exhibiting qualities such as caring attitude, respect and integrity.',
                35 => 'Participate in professional networks to share knowledge and to enhance practice.',
                36 => 'Develop a personal professional improvement plan based on reflection of one’s practice and ongoing professional learning.',
                37 => 'Set professional development goals based on the Philippine Professional Standards for Teachers.',
            ],

            'MT II – MT III' => [
                1 => 'Model effective applications of content knowledge within and across curriculum teaching areas.',
                2 => 'Collaborate with colleagues in the conduct and application of research to enrich knowledge of content and pedagogy.',
                3 => 'Promote effective strategies in the positive use of ICT to facilitate the teaching and learning process.',
                4 => 'Evaluate with colleagues the effectiveness of teaching strategies that promote learner achievement in literacy and numeracy.',
                5 => 'Develop and apply effective teaching strategies to promote critical and creative thinking, as well as other higher-order thinking skills.',
                6 => 'Model and support colleagues in the proficient use of Mother Tongue, Filipino and English to improve teaching and learning, as well as to develop the learners’ pride of their language, heritage and culture.',
                7 => 'Display a wide range of effective verbal and non-verbal classroom communication strategies to support learner understanding, participation, engagement and achievement.',
                // Domain 2
                8 => 'Exhibit effective strategies that ensure safe and secure learning environments to enhance learning through the consistent implementation of policies, guidelines and procedures.',
                9 => 'Exhibit effective practices to foster learning environments that promote fairness, respect and care to encourage learning.',
                10 => 'Work with colleagues to model and share effective techniques in the management of classroom structure to engage learners, individually or in groups, in meaningful exploration, discovery and hands-on activities within a range of physical learning environments.',
                11 => 'Work with colleagues to share successful strategies that sustain supportive learning environments that nurture and inspire learners to participate, cooperate and collaborate in continued learning.',
                12 => 'Model successful strategies and support colleagues in promoting learning environments that effectively motivate learners to work productively by assuming responsibility for their own learning.',
                13 => 'Exhibit effective and constructive behavior management skills by applying positive and non-violent discipline to ensure learning focused environments.',
                // Domain 3
                14 => 'Work with colleagues to share differentiated, developmentally appropriate opportunities to address learners’ differences in gender, needs, strengths, interests and experiences.',
                15 => 'Exhibit a learner-centered culture that promotes success by using effective teaching strategies that respond to learners\' linguistic, cultural, socio economic and religious backgrounds.',
                16 => 'Assist colleagues to design, adapt and implement teaching strategies that are responsive to learners with disabilities, giftedness and talents.',
                17 => 'Evaluate with colleagues teaching strategies that are responsive to the special educational needs of learners in difficult circumstances, including: geographic isolation; chronic illness; displacement due to armed conflict, urban resettlement or disasters; child abuse and child labor practices.',
                18 => 'Develop and apply teaching strategies to address effectively the needs of learners from indigenous groups.',
                // Domain 4
                19 => 'Develop and apply effective strategies in the planning and management of developmentally sequenced teaching and learning processes to meet curriculum requirements and varied teaching contexts.',
                20 => 'Model to colleagues the setting of achievable and challenging learning outcomes that are aligned with learning competencies to cultivate a culture of excellence for all learners.',
                21 => 'Work collaboratively with colleagues to evaluate the design of learning programs that develop the knowledge and skills of learners at different ability levels.',
                22 => 'Review with colleagues, teacher and learner feedback to plan, facilitate, and enrich teaching practice.',
                23 => 'Advise and guide colleagues in the selection, organization, development and use of appropriate teaching and learning resources, including ICT, to address specific learning goals.',
                // Domain 5
                24 => 'Work collaboratively with colleagues to review the design, selection, organization and use of a range of effective diagnostic, formative and summative assessment strategies consistent with curriculum requirements.',
                25 => 'Interpret collaboratively monitoring and evaluation strategies of attainment data to support learner progress and achievement.',
                26 => 'Use effective strategies for providing timely, accurate and constructive feedback to encourage learners to reflect on and improve their own learning.',
                27 => 'Apply skills in the effective communication of learner needs, progress and achievement to key stakeholders, including parents/guardians.',
                28 => 'Work collaboratively with colleagues to analyze and utilize assessment data to modify practices and programs to further support learner progress and achievement.',
                // Domain 6
                29 => 'Reflect on and evaluate learning environments that are responsive to community contexts.',
                30 => 'Guide colleagues to strengthen relationships with parents/guardians and the wider school community to maximize their involvement in the educative process.',
                31 => 'Discuss with colleagues teaching and learning practices that apply existing codes, laws and regulations applicable to the teaching profession, and the responsibilities specified in the  Code of Ethics for Professional Teachers.',
                32 => 'Exhibit commitment to and support teachers in the implementation of school policies and procedures to foster harmonious relationships with learners, parents and other stakeholders.',
                // Domain 7
                33 => 'Manifest a learner-centered teaching philosophy in various aspects of practice and support colleagues in enhancing their own learner-centered teaching philosophy.',
                34 => 'Identify and utilize personal professional strengths to uphold the dignity of teaching as a profession to help build a positive teaching and learning culture within the school.',
                35 => 'Contribute actively to professional networks within and between schools to improve knowledge and to enhance practice.',
                36 => 'Initiate professional reflections and promote learning opportunities with colleagues to improve practice.',
                37 => 'Reflect on the Philippine Professional Standards for Teachers to plan personal professional development goals and assist colleagues in planning and achieving their own goals.',
            ]
        ];

        foreach ($positions as $positionLevel => $indicators) {
            foreach ($indicators as $order => $text) {
                // Determine domain
                if ($order <= 7) $domain = 1;
                elseif ($order <= 13) $domain = 2;
                elseif ($order <= 18) $domain = 3;
                elseif ($order <= 23) $domain = 4;
                elseif ($order <= 28) $domain = 5;
                elseif ($order <= 32) $domain = 6;
                else $domain = 7;

                DB::table('ppst_indicators')->insert([
                    'position_level' => $positionLevel,
                    'domain' => $domain,
                    'indicator_text' => $text,
                    'order' => $order,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}