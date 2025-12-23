// Webflow Rebrand Automation Script
// This will help automate the rebrand process

const SITE_ID = '6937648cfb0c89dbe6623f0f';
const SITE_TOKEN = 'd773430e10fb0ac50227be78cddd035fecff514a412a491e98e45e606890b82d';
const CMS_TOKEN = '07c9a71a9006e2c20169118435edbfc523f206c6320547aba76739a7d9549ec0';
const API_BASE = 'https://api.webflow.com/v2';

// Your Contact Info
const CONTACT_INFO = {
  email: 'Help@marketingtool.pro',
  phone: '+91 85555744532',
  address: 'F-12 Govinddam Tower, Jaipur 302012',
  company: 'MarketingTool.pro',
  appUrl: 'https://app.marketingtool.pro',
  signupUrl: 'https://app.marketingtool.pro/signup',
  loginUrl: 'https://app.marketingtool.pro/login'
};

async function checkSiteStatus() {
  console.log('🔍 Checking site status...\n');

  try {
    const response = await fetch(`${API_BASE}/sites/${SITE_ID}`, {
      headers: {
        'Authorization': `Bearer ${SITE_TOKEN}`,
        'accept': 'application/json'
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log('✅ Site Status:');
    console.log(`   Name: ${data.displayName}`);
    console.log(`   Short Name: ${data.shortName}`);
    console.log(`   Created: ${new Date(data.createdOn).toLocaleDateString()}`);
    console.log(`   Last Published: ${new Date(data.lastPublished).toLocaleString()}`);
    console.log(`   Timezone: ${data.timeZone}\n`);

    return data;
  } catch (error) {
    console.error('❌ Error checking site:', error.message);
    return null;
  }
}

async function getSitePages() {
  console.log('📄 Getting all pages...\n');

  try {
    const response = await fetch(`${API_BASE}/sites/${SITE_ID}/pages`, {
      headers: {
        'Authorization': `Bearer ${SITE_TOKEN}`,
        'accept': 'application/json'
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log(`✅ Found ${data.pages.length} pages:\n`);

    data.pages.forEach((page, index) => {
      console.log(`   ${index + 1}. ${page.title || 'Untitled'}`);
      console.log(`      ID: ${page.id}`);
      console.log(`      Slug: ${page.slug}`);
      console.log(`      Path: ${page.path || '/'}`);
      console.log('');
    });

    return data.pages;
  } catch (error) {
    console.error('❌ Error getting pages:', error.message);
    return [];
  }
}

async function publishSite() {
  console.log('🚀 Publishing site to www.marketingtool.pro...\n');

  try {
    const response = await fetch(`${API_BASE}/sites/${SITE_ID}/publish`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${SITE_TOKEN}`,
        'accept': 'application/json',
        'content-type': 'application/json'
      },
      body: JSON.stringify({
        publishToWebflowSubdomain: true,
        customDomains: ['www.marketingtool.pro', 'marketingtool.pro']
      })
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(`HTTP error! status: ${response.status}, message: ${JSON.stringify(errorData)}`);
    }

    console.log('✅ Site published successfully!');
    console.log('   🌐 Live at: https://www.marketingtool.pro\n');

    return true;
  } catch (error) {
    console.error('❌ Error publishing site:', error.message);
    return false;
  }
}

async function checkCollections() {
  console.log('📦 Checking CMS collections...\n');

  try {
    const response = await fetch(`${API_BASE}/sites/${SITE_ID}/collections`, {
      headers: {
        'Authorization': `Bearer ${CMS_TOKEN}`,
        'accept': 'application/json'
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    console.log(`✅ Found ${data.collections.length} collections:\n`);

    data.collections.forEach((collection, index) => {
      console.log(`   ${index + 1}. ${collection.displayName}`);
      console.log(`      ID: ${collection.id}`);
      console.log(`      Slug: ${collection.slug}`);
      console.log('');
    });

    return data.collections;
  } catch (error) {
    console.error('❌ Error getting collections:', error.message);
    return [];
  }
}

async function generateRebrandReport() {
  console.log('═══════════════════════════════════════════════════');
  console.log('🎯 MARKETINGTOOL.PRO REBRAND STATUS REPORT');
  console.log('═══════════════════════════════════════════════════\n');

  // Check site
  const siteData = await checkSiteStatus();

  // Check pages
  const pages = await getSitePages();

  // Check collections
  const collections = await checkCollections();

  console.log('═══════════════════════════════════════════════════');
  console.log('📋 MANUAL STEPS REQUIRED IN WEBFLOW DESIGNER:');
  console.log('═══════════════════════════════════════════════════\n');

  console.log('⚠️  These changes MUST be done in Webflow Designer:');
  console.log('   (API cannot change page content/design)\n');

  console.log('1️⃣  LOGO & BRANDING (15 min)');
  console.log('   • Upload MarketingTool.pro logo');
  console.log('   • Replace in Header');
  console.log('   • Replace in Footer');
  console.log('   • Update Favicon\n');

  console.log('2️⃣  FIND & REPLACE TEXT (30 min)');
  console.log('   • Press Cmd/Ctrl + F in Designer');
  console.log('   • Search: "Optimatia"');
  console.log('   • Replace with: "MarketingTool.pro"');
  console.log('   • Replace all instances\n');

  console.log('3️⃣  UPDATE CONTACT INFO (15 min)');
  console.log(`   • Email: ${CONTACT_INFO.email}`);
  console.log(`   • Phone: ${CONTACT_INFO.phone}`);
  console.log(`   • Address: ${CONTACT_INFO.address}`);
  console.log('   • Update in Footer');
  console.log('   • Update in Contact page\n');

  console.log('4️⃣  UPDATE CTA BUTTONS (15 min)');
  console.log(`   • "Sign In" → ${CONTACT_INFO.loginUrl}`);
  console.log(`   • "Sign Up" → ${CONTACT_INFO.signupUrl}`);
  console.log(`   • "Start Free Trial" → ${CONTACT_INFO.signupUrl}`);
  console.log(`   • "Get Started" → ${CONTACT_INFO.signupUrl}\n`);

  console.log('5️⃣  UPDATE NAVIGATION (30 min)');
  console.log('   • Rename "Service" → "Services"');
  console.log('   • Clean up dropdowns');
  console.log('   • Add Company dropdown\n');

  console.log('═══════════════════════════════════════════════════');
  console.log('🚀 READY TO PUBLISH?');
  console.log('═══════════════════════════════════════════════════\n');
  console.log('After completing manual steps in Designer:');
  console.log('   Run: node automate-rebrand.js --publish\n');

  console.log('═══════════════════════════════════════════════════\n');

  return {
    site: siteData,
    pages,
    collections
  };
}

// Main execution
async function main() {
  const args = process.argv.slice(2);

  if (args.includes('--publish')) {
    console.log('🚀 Publishing site...\n');
    await publishSite();
  } else {
    await generateRebrandReport();
  }
}

// Run if called directly
if (import.meta.url === `file://${process.argv[1]}`) {
  main().catch(console.error);
}

export { checkSiteStatus, getSitePages, publishSite, checkCollections, CONTACT_INFO };
