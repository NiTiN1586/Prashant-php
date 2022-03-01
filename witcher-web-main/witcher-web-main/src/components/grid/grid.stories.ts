import { Meta, Story } from '@storybook/vue3';
import { defineComponent } from 'vue';
import { faker } from '@faker-js/faker';

import Container from './container.component.vue';
import Row from './row.component.vue';
import Column from './column.component.vue';

export default {
	title: 'Components / Grid',
	subcomponents: { Container, Row, Column },
} as Meta;

export const Grid: Story = () =>
	defineComponent({
		components: { Container, Row, Column },
		template: `
      <Container class='space-y-8'>
      <Row>
        <Column>
          <div class='bg-red-100 p-6'>
            Column 12: ${faker.lorem.paragraph(5)}
          </div>
        </Column>
      </Row>
      <Row>
        <Column class='w-1/2'>
          <div class='bg-blue-100 p-6'>
            Column 6: ${faker.lorem.paragraph(5)}
          </div>
        </Column>
        <Column class='w-1/2'>
          <div class='bg-green-100 p-6'>
            Column 6: ${faker.lorem.paragraph(5)}
          </div>
        </Column>
      </Row>
      </Container>`,
	});
