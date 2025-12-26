/**
 * Centralized icon exports for better tree-shaking and bundle optimization.
 * Import icons from this file instead of directly from lucide-react.
 * This ensures consistent icon usage across the app and enables better caching.
 */

// Navigation icons
export { Menu, X, ChevronDown } from 'lucide-react';

// Feature icons
export { 
  Sparkles, 
  TrendingUp, 
  Zap, 
  Shield, 
  BarChart3, 
  Target,
  Search,
  Eye
} from 'lucide-react';

// UI icons
export { 
  ArrowRight, 
  CheckCircle, 
  Check,
  Clock 
} from 'lucide-react';

// Social icons
export { 
  Users, 
  Award,
  Youtube,
  Linkedin,
  Twitter,
  Facebook,
  Instagram
} from 'lucide-react';

// Resource icons
export {
  BookOpen,
  ClipboardCheck,
  FileCheck,
  Wrench,
  HelpCircle,
  FileText,
  Gift,
  Mail,
  Briefcase,
  Info,
  Phone,
  MapPin
} from 'lucide-react';

// Type re-exports for TypeScript
export type { LucideIcon } from 'lucide-react';
