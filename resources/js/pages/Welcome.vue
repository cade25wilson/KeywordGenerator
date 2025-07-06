<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

// --- Accordion UI Components (Imported for FAQ section) ---
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion'

// --- Icons (from lucide-vue-next) ---
import {
  ArrowRight,
  Brain,
  Search,
  TrendingUp,
  ShoppingCart,
  Gauge,
  Hand,
  Lightbulb,
  Star,
  Check, // For pricing section
  Linkedin, // For footer
  Instagram, // For footer
  Facebook, // For footer
  Twitter, // For footer
} from 'lucide-vue-next'

import { ref } from 'vue' // 'provide' is no longer needed for accordion
import { Button } from "@/components/ui/button";
// --- DATA & LOGIC FOR ALL SECTIONS ---

// Nav Bar Data (Directly in JS)
const navLinks = [
  { href: '#features', text: 'Features' },
  { href: '#how-it-works', text: 'How It Works' },
  { href: '#pricing', text: 'Pricing' },
  { href: '#testimonials', text: 'Testimonials' },
  { href: '#faqs', text: 'FAQs' },
]

// Hero Section Data (Directly in JS)
// No specific data, just static text and a visual placeholder.

// Logo Cloud Data (Directly in JS)
const logos = [
  { name: 'Shopify' },
  { name: 'WooCommerce' },
  { name: 'Amazon' },
  { name: 'Etsy' },
  { name: 'BigCommerce' },
  { name: 'eBay' },
]

// Features Section Data (Directly in JS)
const features = [
  {
    icon: Brain,
    title: 'Precision-Targeted Keywords',
    description:
      'Generate highly relevant and profitable keywords tailored specifically for your products, powered by advanced AI algorithms.',
    visual_text: '[AI Visual]',
  },
  {
    icon: Search,
    title: 'Spy on Competitor Keywords',
    description:
      'Uncover the exact keywords your top competitors rank for, giving you a strategic advantage to dominate your niche.',
    visual_text: '[Analysis Visual]',
  },
  {
    icon: TrendingUp,
    title: 'Unlock Hidden Traffic Goldmines',
    description:
      'Identify low-competition, high-conversion long-tail keywords that attract ready-to-buy customers specifically searching for your products.',
    visual_text: '[Long-Tail Visual]',
  },
  {
    icon: ShoppingCart,
    title: 'Seamless Listing Optimization',
    description:
      'Integrate directly with popular e-commerce platforms or easily export optimized keyword lists for quick updates to your product pages.',
    visual_text: '[Integration Visual]',
  },
]
const featured = features[0]
const smallerFeatures = features.slice(1)

// Why Choose Us Data (Directly in JS)
const reasons = [
  {
    icon: Gauge, // Represents speed/efficiency
    title: 'Instant Results',
    description:
      'Automate tedious keyword research and get actionable insights in mere minutes, freeing up your valuable time.',
  },
  {
    icon: TrendingUp,
    title: 'Increased Conversions',
    description:
      'Rank higher in search results, attract more qualified traffic, and significantly boost your product sales.',
  },
  {
    icon: Hand, // Represents ease of use/friendliness
    title: 'Effortless Optimization',
    description:
      'Designed for busy product managers and marketers – intuitive, clean, and straightforward to use, no SEO expertise required.',
  },
  {
    icon: Lightbulb,
    title: 'Always Ahead',
    description:
      'Our AI constantly learns and updates, ensuring you always have access to the freshest, most effective keyword data.',
  },
]

// Pricing Section Data
const pricingPlans = [
  {
    name: 'Starter',
    price: '29',
    description: 'For individuals and small teams getting started.',
    features: [
      '100 keyword reports per month',
      'Competitor keyword analysis (5 competitors)',
      'Basic listing optimizer',
      'Email support',
    ],
    isFeatured: false,
  },
  {
    name: 'Pro',
    price: '79',
    description: 'For growing businesses and marketing professionals.',
    features: [
      'Unlimited keyword reports',
      'Competitor keyword analysis (25 competitors)',
      'Advanced listing optimizer',
      'Trend analysis & insights',
      'Priority email & chat support',
    ],
    isFeatured: true,
  },
  {
    name: 'Enterprise',
    price: 'Contact Us',
    description: 'For large-scale operations and agencies.',
    features: [
      'Everything in Pro, plus:',
      'API access for integrations',
      'Team collaboration features',
      'Dedicated account manager',
      'Custom onboarding & training',
    ],
    isFeatured: false,
  },
]


// Testimonials Data (Directly in JS)
const testimonials = [
  {
    rating: 5,
    text: 'ProductKeywordAI transformed our product listings! We saw a 30% increase in organic traffic and sales within the first month. The keyword suggestions are incredibly accurate.',
    name: 'Sarah L.',
    company: 'E-commerce Manager',
  },
  {
    rating: 5,
    text: 'As a small business owner, I struggled with SEO. This tool made it simple to find lucrative keywords, and my product visibility has never been better. Highly recommend!',
    name: 'Mark T.',
    company: 'Boutique Store Owner',
  },
  {
    rating: 5,
    text: 'The competitor keyword spy feature is a game-changer. We\'ve optimized our strategy based on their insights and are now outranking major players. Essential for any product business.',
    name: 'Jessica R.',
    company: 'Digital Marketing Consultant',
  },
]

// FAQ Section Data (Directly in JS)
const faqs = [
  {
    id: 'faq-1',
    question: 'How does ProductKeywordAI generate keywords?',
    answer:
      'Our platform uses proprietary AI and machine learning algorithms to analyze vast amounts of data, including search trends, competitor strategies, and product attributes, to deliver highly relevant and effective keywords.',
  },
  {
    id: 'faq-2',
    question: 'What e-commerce platforms does ProductKeywordAI support?',
    answer:
      'ProductKeywordAI generates keywords optimized for all major e-commerce platforms and marketplaces including Shopify, Amazon, Etsy, WooCommerce, Magento, and more. You can easily export lists for any platform.',
  },
  {
    id: 'faq-3',
    question: 'Is there a free trial available?',
    answer:
      'Yes! We offer a 7-day free trial that gives you full access to all our features. No credit card required to get started.',
  },
  {
    id: 'faq-4',
    question: 'How accurate are the keyword suggestions?',
    answer:
      'Our AI is constantly being refined to provide the most accurate and high-performing keyword suggestions. Customers typically report significant improvements in search ranking and traffic.',
  },
  {
    id: 'faq-5',
    question: 'Can I track my keyword performance within the tool?',
    answer:
      'While ProductKeywordAI focuses on keyword generation, we provide seamless export options to integrate with your preferred analytics tools for performance tracking.',
  },
]

// CTA Banner Data (Directly in JS)
// No specific data, just static text and a visual placeholder.

// Footer Data (Directly in JS)
const currentYear = new Date().getFullYear()

</script>

<template>

  <Head title="Welcome">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head>
  <div class="flex flex-col min-h-screen">
    <!-- Nav Bar -->
    <nav class="flex items-center justify-between p-4 md:px-8 max-w-7xl mx-auto w-full bg-white">
      <a href="#" class="font-bold text-2xl text-gray-900"> ProductKeywordAI </a>
      <div class="hidden md:flex items-center space-x-6">
        <a v-for="link in navLinks" :key="link.href" :href="link.href" class="text-gray-600 hover:text-gray-900">
          {{ link.text }}
        </a>
      </div>
      <div class="flex items-center space-x-2">
        <Button variant="ghost" as="a" href="/login">Login</Button>
        <Button as="a" href="#cta">Get Started Free</Button>
      </div>
    </nav>

    <main class="flex-grow">
      <!-- Hero Section -->
      <section class="relative bg-gray-50 py-16 md:py-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
          <!-- Left Content -->
          <div class="flex-1 text-center md:text-left">
            <div
              class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm font-medium mb-4">
              🚀 Trusted by 5,000+ E-commerce Brands
              <ArrowRight class="ml-2 h-4 w-4" />
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
              Unlock Product Visibility with AI-Powered Keywords
            </h1>
            <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-lg mx-auto md:mx-0">
              Effortlessly discover high-impact, buyer-intent keywords that drive traffic and sales
              directly to your product listings.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
              <Button size="lg" class="px-8 py-3 w-full sm:w-auto h-auto">
                Start Generating Keywords
              </Button>
              <Button variant="outline" size="lg" as="a" href="#how-it-works" class="px-8 py-3 w-full sm:w-auto h-auto">
                See How It Works
              </Button>
            </div>
            <p class="mt-8 text-sm text-gray-500">Loved by 10,000+ Products Optimized</p>
          </div>

          <!-- Right Visual Placeholder -->
          <!-- <div class="flex-1 flex justify-center md:justify-end">
            <div
              class="w-full max-w-md h-72 md:h-96 bg-gray-300 rounded-xl shadow-lg flex items-center justify-center text-gray-500 font-semibold text-center p-4">
              [Placeholder: App Preview / Product Dashboard Mockup]
            </div>
          </div> -->
        </div>
      </section>

      <!-- Logo Cloud -->
      <!-- <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 md:px-8 text-center">
     <h2 class="text-lg md:text-xl font-semibold text-gray-500 mb-8">
      Trusted by Leading E-commerce Businesses & Agencies
     </h2>
     <div
      class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 items-center justify-items-center"
     >
      <div
       v-for="(logo, index) in logos"
       :key="index"
       class="flex items-center justify-center h-16 w-32 bg-gray-100 rounded-lg text-gray-600 font-medium text-sm"
      >
       {{ logo.name }}
      </div>
     </div>
    </div>
   </section> -->

      <!-- Features Section -->
      <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Highlight what sets your app apart from others
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <!-- Main Feature Card -->
            <div class="bg-white rounded-lg shadow-lg flex flex-col md:flex-row items-center p-6 md:p-8">
              <div class="md:w-2/3 md:pr-6 text-center md:text-left mb-6 md:mb-0">
                <component :is="featured.icon" class="h-10 w-10 text-purple-600 mb-4 mx-auto md:mx-0" />
                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                  {{ featured.title }}
                </h3>
                <p class="text-gray-600">
                  {{ featured.description }}
                </p>
              </div>
              <div
                class="md:w-1/3 bg-gray-200 h-48 md:h-auto rounded-lg flex items-center justify-center text-gray-500">
                {{ featured.visual_text }}
              </div>
            </div>

            <!-- Smaller Feature Cards -->
            <div class="grid grid-cols-1 gap-8 md:mt-0">
              <div v-for="(feature, index) in smallerFeatures" :key="index"
                class="bg-white rounded-lg shadow-md flex flex-col sm:flex-row items-center p-6">
                <div class="sm:pr-6 text-center sm:text-left mb-4 sm:mb-0">
                  <component :is="feature.icon" class="h-8 w-8 text-purple-600 mb-3 mx-auto sm:mx-0" />
                  <h3 class="text-xl font-bold text-gray-900 mb-2">
                    {{ feature.title }}
                  </h3>
                  <p class="text-gray-600 text-sm">
                    {{ feature.description }}
                  </p>
                </div>
                <div
                  class="flex-shrink-0 w-full sm:w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs text-center p-2">
                  {{ feature.visual_text }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Why Choose Us Section -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Make your strengths obvious.
          </h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div v-for="(reason, index) in reasons" :key="index" class="text-center p-6 bg-white rounded-lg shadow-sm">
              <component :is="reason.icon" class="h-10 w-10 text-purple-600 mx-auto mb-4" />
              <h3 class="text-xl font-semibold text-gray-900 mb-3">
                {{ reason.title }}
              </h3>
              <p class="text-gray-600 text-sm">
                {{ reason.description }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Pricing Section -->
      <section id="pricing" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-8 text-center">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Simple, Transparent Pricing
          </h2>
          <p class="text-lg text-gray-600 mb-12">
            Choose the plan that's right for your business.
          </p>
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div v-for="(plan, index) in pricingPlans" :key="index" :class="[
              'relative flex flex-col rounded-2xl border p-8 text-left',
              plan.isFeatured ? 'border-purple-500 ring-2 ring-purple-500' : 'border-gray-200'
            ]">
              <div v-if="plan.isFeatured" class="absolute top-0 -translate-y-1/2 left-1/2 -translate-x-1/2">
                <span
                  class="inline-flex items-center px-4 py-1 bg-purple-600 text-white text-sm font-semibold rounded-full">
                  Most Popular
                </span>
              </div>

              <h3 class="text-2xl font-bold text-gray-900">{{ plan.name }}</h3>
              <p class="text-gray-500 mt-2 mb-6">{{ plan.description }}</p>

              <div class="my-4">
                <span v-if="plan.price !== 'Contact Us'" class="text-5xl font-extrabold text-gray-900">${{ plan.price
                  }}</span>
                <span v-if="plan.price !== 'Contact Us'" class="text-lg font-medium text-gray-500">/month</span>
                <span v-else class="text-3xl font-extrabold text-gray-900">Contact Us</span>
              </div>

              <ul class="space-y-4 text-gray-700 flex-grow">
                <li v-for="feature in plan.features" :key="feature" class="flex items-start">
                  <Check class="h-6 w-6 text-purple-500 mr-2 flex-shrink-0" />
                  <span>{{ feature }}</span>
                </li>
              </ul>

              <Button :variant="plan.isFeatured ? 'default' : 'outline'" size="lg" class="mt-8 w-full" as="a"
                :href="plan.price === 'Contact Us' ? '#contact' : '/register'">
                {{ plan.price === 'Contact Us' ? 'Contact Sales' : 'Get Started' }}
              </Button>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section id="testimonials" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Let happy users convince the rest.
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="(testimonial, index) in testimonials" :key="index"
              class="bg-white rounded-lg shadow-md p-6 flex flex-col">
              <div class="flex mb-4">
                <Star v-for="n in testimonial.rating" :key="n" class="h-5 w-5 fill-yellow-400 text-yellow-400" />
              </div>
              <p class="text-gray-700 leading-relaxed mb-4 flex-grow">
                &ldquo;{{ testimonial.text }}&rdquo;
              </p>
              <div class="font-semibold text-gray-900">
                {{ testimonial.name }}
              </div>
              <div class="text-sm text-gray-500">
                {{ testimonial.company }}
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section id="faqs" class="py-16 bg-white ">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 text-center mb-12">
            Frequently Asked Questions
          </h2>
          <div class="max-w-3xl mx-auto ">
            <Accordion type="single" class="w-full" collapsible>
              <AccordionItem v-for="faq in faqs" :key="faq.id" :value="faq.id">
                <AccordionTrigger class="text-black text-left cursor-pointer">{{ faq.question }}</AccordionTrigger>
                <AccordionContent>
                  <p class="text-base text-gray-600">{{ faq.answer }}</p>
                </AccordionContent>
              </AccordionItem>
            </Accordion>
          </div>
        </div>
      </section>

      <!-- CTA Banner -->
      <section id="cta" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
          <div
            class="bg-gradient-to-r from-purple-700 to-indigo-600 rounded-2xl p-8 md:p-16 flex flex-col md:flex-row items-center justify-between text-white text-center md:text-left gap-8">
            <!-- Left Content -->
            <div class="flex-1">
              <h2 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">
                Ready to Transform Your Product Visibility & Sales?
              </h2>
              <p class="text-lg opacity-90">
                Join thousands of product businesses already using ProductKeywordAI to skyrocket their
                organic traffic and unlock unprecedented sales.
              </p>
            </div>

            <!-- Right CTA Buttons & Visual Placeholder -->
            <div class="flex-1 flex flex-col items-center md:items-end gap-6 w-full md:w-auto">
              <!-- Visual Placeholder -->
              <div
                class="w-full max-w-[200px] h-32 bg-purple-500 rounded-lg shadow-xl mb-4 md:mb-0 flex items-center justify-center text-purple-100 text-sm italic">
                [Visual: Phone Mockup/Graph]
              </div>

              <div class="flex flex-col sm:flex-row gap-4 w-full justify-center md:justify-end">
                <Button size="lg" class="bg-white text-purple-700 hover:bg-gray-100 px-8 py-3 w-full sm:w-auto h-auto">
                  Start Your Free Trial Today
                </Button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-12">
      <div class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="text-center sm:text-left">
          &copy; {{ currentYear }} ProductKeywordAI. All Rights Reserved.
        </div>
        <div class="flex items-center space-x-6">
          <a href="#" class="hover:text-white">
            <Linkedin class="h-6 w-6" />
          </a>
          <a href="#" class="hover:text-white">
            <Instagram class="h-6 w-6" />
          </a>
          <a href="#" class="hover:text-white">
            <Facebook class="h-6 w-6" />
          </a>
          <a href="#" class="hover:text-white">
            <Twitter class="h-6 w-6" />
          </a>
        </div>
        <div class="flex flex-wrap justify-center sm:justify-end gap-x-6 gap-y-2 text-sm">
          <a href="#" class="hover:text-white">Privacy Policy</a>
          <a href="#" class="hover:text-white">Terms of Service</a>
          <a href="#" class="hover:text-white">Support</a>
          <a href="#" class="hover:text-white">Blog</a>
        </div>
      </div>
    </footer>
  </div>
</template>