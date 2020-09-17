describe('Dashboard - employee - one on one', function () {
  it('should let the employee starts a one on one with his manager', function () {
    cy.login()

    cy.createCompany()

    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'user', true)

    cy.visit('/1/employees/1')

    // assign Michael scott as the manager so we can validate a task has been created for the manager later on
    cy.assignManager('scott')

    cy.visit('/1/dashboard/me')

    cy.get('[data-cy=view-one-on-one-1]').click()

    // add a new talking point
    cy.get('[data-cy=add-new-talking-point]').click()
    cy.get('[data-cy=talking-point-description-textarea]').clear()
    cy.get('[data-cy=talking-point-description-textarea]').type('new talking point')
    cy.get('[data-cy=add-talking-point-cta]').click()

    // make sure the talking point exists
    cy.get('[data-cy=talking-point-1]').should('exist')
    cy.get('[data-cy=talking-point-1]').contains('new talking point')

    // edit the talking point
    cy.get('[data-cy=talking-point-1]').trigger('mouseover')
    cy.get('[data-cy=talking-point-1-edit]').click()
    cy.get('[data-cy=edit-talking-point-description-textarea-1]').clear()
    cy.get('[data-cy=edit-talking-point-description-textarea-1]').type('another talking point')
    cy.get('[data-cy=edit-talking-point-cta]').click()

    // make sure the talking point exists
    cy.get('[data-cy=talking-point-1]').should('exist')
    cy.get('[data-cy=talking-point-1]').contains('another talking point')

    // delete the talking point
    cy.get('[data-cy=talking-point-1]').trigger('mouseover')
    cy.get('[data-cy=talking-point-1-delete]').click()
    cy.get('[data-cy=talking-point-1-delete-cta]').click()
    cy.get('[data-cy=talking-point-1]').should('not.exist')

    // add a new action item
    cy.get('[data-cy=add-new-action-item]').click()
    cy.get('[data-cy=action-item-description-textarea]').clear()
    cy.get('[data-cy=action-item-description-textarea]').type('new action item')
    cy.get('[data-cy=add-action-item-cta]').click()

    // make sure the action item exists
    cy.get('[data-cy=action-item-1]').should('exist')
    cy.get('[data-cy=action-item-1]').contains('new action item')

    // edit the action item
    cy.get('[data-cy=action-item-1]').trigger('mouseover')
    cy.get('[data-cy=action-item-1-edit]').click()
    cy.get('[data-cy=edit-action-item-description-textarea-1]').clear()
    cy.get('[data-cy=edit-action-item-description-textarea-1]').type('another action item')
    cy.get('[data-cy=edit-action-item-cta]').click()

    // make sure the action item exists
    cy.get('[data-cy=action-item-1]').should('exist')
    cy.get('[data-cy=action-item-1]').contains('another action item')

    // delete the action item
    cy.get('[data-cy=action-item-1]').trigger('mouseover')
    cy.get('[data-cy=action-item-1-delete]').click()
    cy.get('[data-cy=action-item-1-delete-cta]').click()
    cy.get('[data-cy=action-item-1]').should('not.exist')

    // add a new note
    cy.get('[data-cy=add-new-note]').click()
    cy.get('[data-cy=add-note-textarea]').clear()
    cy.get('[data-cy=add-note-textarea]').type('new note')
    cy.get('[data-cy=add-new-note-cta]').click()

    // make sure the note exists
    cy.get('[data-cy=note-1]').contains('new note')
    cy.get('[data-cy=note-1]').should('exist')

    // edit a note
    cy.get('[data-cy=note-1]').trigger('mouseover')
    cy.get('[data-cy=edit-note-1]').click()
    cy.get('[data-cy=edit-note-description-textarea-1]').clear()
    cy.get('[data-cy=edit-note-description-textarea-1]').type('updated note')
    cy.get('[data-cy=edit-note-cta]').click()
    cy.get('[data-cy=note-1]').contains('updated note')

    // delete a note
    cy.get('[data-cy=delete-note-1]').click()
    cy.get('[data-cy=delete-note-cta-1]').click()

    // create 3 additional action items, mark the entry as happened
    // 1 of those action items should be checked before
    // this should create a new entry with 2 talking points
    cy.get('[data-cy=add-new-action-item]').click()
    cy.get('[data-cy=action-item-description-textarea]').clear()
    cy.get('[data-cy=action-item-description-textarea]').type('action item 1')
    cy.get('[data-cy=add-action-item-cta]').click()

    cy.get('[data-cy=add-new-action-item]').click()
    cy.get('[data-cy=action-item-description-textarea]').clear()
    cy.get('[data-cy=action-item-description-textarea]').type('action item 2')
    cy.get('[data-cy=add-action-item-cta]').click()

    cy.get('[data-cy=add-new-action-item]').click()
    cy.get('[data-cy=action-item-description-textarea]').clear()
    cy.get('[data-cy=action-item-description-textarea]').type('action item 3')
    cy.get('[data-cy=add-action-item-cta]').click()

    // check one of these items
    cy.get('[data-cy=action-item-2-single-item]').check()

    cy.get('[data-cy=entry-mark-as-happened-button]').click()

    // check that there are 2 talking points
    cy.get('[data-cy=talking-point-2]').contains('action item 2')
    cy.get('[data-cy=talking-point-3]').contains('action item 3')

    // go to the employee page to check if there are entries
    cy.visit('/1/employees/1')
    cy.get('[data-cy=entry-item-1]').should('exist')
    cy.get('[data-cy=entry-item-2]').should('exist')

    cy.get('[data-cy=entry-item-1]').click()
    cy.url().should('include', '/1/oneonones/1')

    cy.visit('/1/employees/1')
    cy.get('[data-cy=view-all-one-on-ones]').click()

    // go back to the list of all one on ones
    cy.get('[data-cy=entry-list-item-1]').should('exist')
    cy.get('[data-cy=entry-list-item-2]').should('exist')
    cy.get('[data-cy=entry-list-item-1]').click()
    cy.get('[data-cy=view-all-one-on-ones]').click()
    cy.get('[data-cy=entry-list-item-2]').click()
  })
})
